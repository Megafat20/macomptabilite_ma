@extends('layouts.app')

@section('title', 'Détails de la Facture')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Facture #{{ $facture->numero_facture ?? 'N/A' }}
                        <small>{{ $facture->created_at->format('d/m/Y') }}</small>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('factures.edit', $facture->id) }}" class="btn btn-warning btn-sm"><i
                                class="fas fa-edit"></i> Modifier</a>
                        <a href="{{ route('factures.index') }}" class="btn btn-default btn-sm">Retour</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-building mr-1"></i> Entreprise</strong>
                            <p class="text-muted">{{ $facture->entreprise->nom ?? 'Inconnue' }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-user mr-1"></i> Tiers</strong>
                            <p class="text-muted">{{ $facture->tiers }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-exchange-alt mr-1"></i> Type</strong>
                            <p>
                                @if ($facture->type == 'actif')
                                    <span class="badge badge-success">Actif (Client)</span>
                                @else
                                    <span class="badge badge-danger">Passif (Fournisseur)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-calendar mr-1"></i> Date Facture</strong>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-money-bill-wave mr-1"></i> Montant</strong>
                            <p class="text-success font-weight-bold">{{ number_format($facture->montant, 2) }} DH</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-align-left mr-1"></i> Description</strong>
                            <p class="text-muted">{{ $facture->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Pièces Jointes</h3>
                </div>
                <div class="card-body">
                    @if ($facture->pieces_jointes->count() > 0)
                        <div class="row">
                            @foreach ($facture->pieces_jointes as $piece)
                                <div class="col-md-3">
                                    <div class="card mb-3 shadow-sm">
                                        @php
                                            $extension = strtolower(
                                                pathinfo($piece->chemin_fichier, PATHINFO_EXTENSION),
                                            );
                                        @endphp

                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/' . $piece->chemin_fichier) }}" class="card-img-top"
                                                alt="Image" style="height: 150px; object-fit: cover;">
                                        @elseif($extension === 'pdf')
                                            <div class="text-center p-3"
                                                style="height: 150px; background-color: #f4f6f9; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                            </div>
                                        @else
                                            <div class="text-center p-3"
                                                style="height: 150px; background-color: #f4f6f9; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-file fa-4x text-secondary"></i>
                                            </div>
                                        @endif

                                        <div class="card-body p-2 text-center">
                                            <a href="{{ asset('storage/' . $piece->chemin_fichier) }}" target="_blank"
                                                class="btn btn-sm btn-primary btn-block">
                                                <i class="fas fa-download"></i> Voir / Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune pièce jointe.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
