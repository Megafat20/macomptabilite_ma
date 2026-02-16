@extends('layouts.app')

@section('title', 'Liste des Factures')

@section('content')
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Actif (Recettes)</span>
                    <span class="info-box-number">{{ number_format($totalActif ?? 0, 2) }} DH</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Passif (Dépenses)</span>
                    <span class="info-box-number">{{ number_format($totalPassif ?? 0, 2) }} DH</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box {{ ($solde ?? 0) >= 0 ? 'bg-info' : 'bg-secondary' }}">
                <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Solde</span>
                    <span class="info-box-number">{{ number_format($solde ?? 0, 2) }} DH</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Factures</h3>
            <div class="card-tools">
                <a href="{{ route('factures.create', ['entreprise_id' => request('entreprise_id')]) }}"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvelle Facture
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('factures.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="entreprise_id" class="form-control mb-2">
                            <option value="">-- Toutes les entreprises --</option>
                            @foreach ($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}"
                                    {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-control mb-2">
                            <option value="">-- Type --</option>
                            <option value="actif" {{ request('type') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="passif" {{ request('type') == 'passif' ? 'selected' : '' }}>Passif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="statut" class="form-control mb-2">
                            <option value="">-- Statut --</option>
                            <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                            <option value="impaye" {{ request('statut') == 'impaye' ? 'selected' : '' }}>Impayé</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control mb-2" placeholder="N° Facture ou Tiers"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex">
                            <input type="date" name="date_debut" class="form-control mb-2 mr-1"
                                value="{{ request('date_debut') }}" title="Date début">
                            <input type="date" name="date_fin" class="form-control mb-2"
                                value="{{ request('date_fin') }}" title="Date fin">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-default btn-block"><i class="fas fa-filter"></i>
                            Filtrer</button>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="export" value="1" class="btn btn-success btn-block"><i
                                class="fas fa-file-excel"></i> Exporter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Entreprise</th>
                            <th>Tiers</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Montant</th>
                            <th>N° Facture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</td>
                                <td>{{ $facture->entreprise->nom ?? 'N/A' }}</td>
                                <td>{{ $facture->tiers }}</td>
                                <td>
                                    @if ($facture->type == 'actif')
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Passif</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($facture->statut == 'paye')
                                        <button type="button" class="btn badge badge-success btn-status-action"
                                            data-toggle="modal" data-target="#statusModal" data-id="{{ $facture->id }}"
                                            data-statut="paye" style="border:none; cursor:pointer;">
                                            Payé
                                        </button>
                                    @else
                                        <button type="button" class="btn badge badge-warning btn-status-action btn-sparkle"
                                            data-toggle="modal" data-target="#statusModal" data-id="{{ $facture->id }}"
                                            data-statut="impaye" style="border:none; cursor:pointer;">
                                            Impayé
                                        </button>
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($facture->montant, 2) }} DH</td>
                                <td>{{ $facture->numero_facture }}</td>
                                <td>
                                    <a href="{{ route('factures.show', $facture->id) }}" class="btn btn-info btn-xs"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('factures.edit', $facture->id) }}"
                                        class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('factures.destroy', $facture->id) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucune facture trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $factures->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Modal Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="statusForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Modifier le statut</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modalStatut">Statut de la facture</label>
                            <select name="statut" id="modalStatut" class="form-control">
                                <option value="paye">Payé</option>
                                <option value="impaye">Impayé</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('#statusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var statut = button.data('statut')

            var modal = $(this)
            // Update form action dynamically
            var url = "{{ route('factures.update-status', ':id') }}";
            url = url.replace(':id', id);
            modal.find('#statusForm').attr('action', url);

            // Set current status
            modal.find('#modalStatut').val(statut);
        })
    </script>
@endpush
