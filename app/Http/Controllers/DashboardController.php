<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        // Global Stats
        $stats = [
            'total_invoices' => Invoice::count(),
            'total_actif' => Invoice::where('type', 'actif')->sum('montant'),
            'total_passif' => Invoice::where('type', 'passif')->sum('montant'),
            'companies_count' => Company::count(),
        ];

        // Stats par entreprise
        $companiesStats = Company::withCount('factures') // 'factures' est la relation définie dans le modèle
            ->withSum(['factures as total_actif' => function ($query) {
                $query->where('type', 'actif');
            }], 'montant')
            ->withSum(['factures as total_passif' => function ($query) {
                $query->where('type', 'passif');
            }], 'montant')
            ->get();

        // Activités récentes (Dernières 24h ou 10 dernières factures)
        $recentInvoices = Invoice::with(['entreprise'])
                                ->latest('created_at')
                                ->take(7)
                                ->get();
                                
        // Données pour le graphique (6 derniers mois)
        $chartData = [
            'labels' => [],
            'actif' => [],
            'passif' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $year = $date->year;
            $month = $date->month;

            $chartData['labels'][] = $monthName;
            $chartData['actif'][] = Invoice::where('type', 'actif')
                ->whereYear('date_facture', $year)
                ->whereMonth('date_facture', $month)
                ->sum('montant');
            $chartData['passif'][] = Invoice::where('type', 'passif')
                ->whereYear('date_facture', $year)
                ->whereMonth('date_facture', $month)
                ->sum('montant');
        }

        return view('dashboard', compact('stats', 'companiesStats', 'recentInvoices', 'chartData'));
    }
}
