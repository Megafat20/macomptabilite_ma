<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Company;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::query()->with(['entreprise', 'pieces_jointes']);

        if ($request->filled('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'LIKE', "%{$search}%")
                  ->orWhere('tiers', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('date_debut')) {
            $query->whereDate('date_facture', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('date_facture', '<=', $request->date_fin);
        }

        // Export Excel/CSV logic
        if ($request->has('export') && $request->export == '1') {
            $filename = "factures_" . date('Y-m-d_H-i') . ".csv";
            $headers = array(
                "Content-type"        => "text/csv; charset=utf-8",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            // Add Byte Order Mark (BOM) for UTF-8 compatibility in Excel
            $callback = function() use($query) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, array('Date', 'Entreprise', 'Tiers', 'Type', 'Statut', 'N° Facture', 'Montant', 'Description'));

                // Chunking for performance
                $query->orderBy('date_facture', 'desc')->chunk(100, function($invoices) use($file) {
                    foreach ($invoices as $invoice) {
                        fputcsv($file, array(
                            $invoice->date_facture,
                            $invoice->entreprise->nom ?? 'N/A',
                            $invoice->tiers,
                            ucfirst($invoice->type),
                            ucfirst($invoice->statut),
                            $invoice->numero_facture,
                            $invoice->montant,
                            $invoice->description
                        ));
                    }
                });
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $factures = $query->latest('date_facture')->paginate(10);
        $entreprises = Company::all();

        // Calcul des stats pour la vue (en prenant en compte les filtres sauf le type/statut si on veut une vue globale, mais ici on veut les stats de la sélection)
        // Pour les stats du haut, on veut souvent les totaux globaux de ce qu'on voit.
        // On va cloner la query principale pour avoir les sommes exactes des filtres appliqués
        
        // Note: Si on pagine, la somme doit être sur TOTAL pas juste la page.
        // Donc on réutilise les conditions de base.
         
        $statsQuery = Invoice::query();
        // Réappliquer les filtres pour les stats
         if ($request->filled('entreprise_id')) $statsQuery->where('entreprise_id', $request->entreprise_id);
         if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function($q) use ($search) {
                $q->where('numero_facture', 'LIKE', "%{$search}%")
                  ->orWhere('tiers', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('date_debut')) $statsQuery->whereDate('date_facture', '>=', $request->date_debut);
        if ($request->filled('date_fin')) $statsQuery->whereDate('date_facture', '<=', $request->date_fin);
        
        // On calcule Actif et Passif INDÉPENDAMMENT du filtre 'type' existant pour voir les deux colones si possible,
        // OU on respecte le filtre. Si je filtre "ACTIF", je veux voir 0 en Passif.
        if ($request->filled('type')) $statsQuery->where('type', $request->type);
        if ($request->filled('statut')) $statsQuery->where('statut', $request->statut);


        $totalActif = (clone $statsQuery)->where('type', 'actif')->sum('montant');
        $totalPassif = (clone $statsQuery)->where('type', 'passif')->sum('montant');
        
        // Si le filtre TYPE est appliqué, l'une des sommes sera 0, ce qui est logique.
        
        $solde = $totalActif - $totalPassif;

        return view('factures.index', compact('factures', 'entreprises', 'totalActif', 'totalPassif', 'solde'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $entreprises = Company::all();
        $selectedEntrepriseId = $request->get('entreprise_id');
        return view('factures.create', compact('entreprises', 'selectedEntrepriseId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'type' => 'required|in:actif,passif',
            'tiers' => 'required|string|max:255',
            'numero_facture' => 'nullable|string|max:255',
            'date_facture' => 'required|date',
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'pieces_jointes.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $facture = Invoice::create($request->all());

        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $path = $file->store('attachments', 'public');
                Attachment::create([
                    'facture_id' => $facture->id,
                    'chemin_fichier' => $path,
                    'type_fichier' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('factures.index')->with('success', 'Facture ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $facture = Invoice::with(['entreprise', 'pieces_jointes'])->findOrFail($id);
        return view('factures.show', compact('facture'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $facture = Invoice::findOrFail($id);
        $entreprises = Company::all();
        return view('factures.edit', compact('facture', 'entreprises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'type' => 'required|in:actif,passif',
            'tiers' => 'required|string|max:255',
            'numero_facture' => 'nullable|string|max:255',
            'date_facture' => 'required|date',
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'pieces_jointes.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $facture = Invoice::findOrFail($id);
        $facture->update($request->except('pieces_jointes'));

        if ($request->hasFile('pieces_jointes')) {
             foreach ($request->file('pieces_jointes') as $file) {
                $path = $file->store('attachments', 'public');
                Attachment::create([
                    'facture_id' => $facture->id,
                    'chemin_fichier' => $path,
                    'type_fichier' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('factures.index')->with('success', 'Facture mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $facture = Invoice::findOrFail($id);
        // Optionally delete files from storage here if needed
        $facture->delete();

        return redirect()->route('factures.index')->with('success', 'Facture supprimée avec succès.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'statut' => 'required|in:paye,impaye',
        ]);

        $facture = Invoice::findOrFail($id);
        $facture->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut de la facture mis à jour.');
    }
}
