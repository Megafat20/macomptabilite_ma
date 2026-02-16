@extends('layouts.app')

@section('title', 'Nouvelle Facture')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Ajouter une facture</h3>
        </div>
        <form action="{{ route('factures.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    @if ($selectedEntrepriseId)
                        {{-- Si l'entreprise est déjà sélectionnée, on la cache et on affiche juste le nom --}}
                        <input type="hidden" name="entreprise_id" value="{{ $selectedEntrepriseId }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Entreprise</label>
                                <input type="text" class="form-control"
                                    value="{{ $entreprises->find($selectedEntrepriseId)->nom ?? 'N/A' }}" disabled>
                                <small class="text-muted">L'entreprise est pré-sélectionnée selon votre filtre</small>
                            </div>
                        </div>
                    @else
                        {{-- Sinon on affiche le select normal --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="entreprise_id">Entreprise</label>
                                <select name="entreprise_id" id="entreprise_id" class="form-control" required>
                                    <option value="">Sélectionner une entreprise</option>
                                    @foreach ($entreprises as $entreprise)
                                        <option value="{{ $entreprise->id }}"
                                            {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                            {{ $entreprise->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="actif" {{ old('type') == 'actif' ? 'selected' : '' }}>Actif (Facture Client)
                                </option>
                                <option value="passif" {{ old('type') == 'passif' ? 'selected' : '' }}>Passif (Facture
                                    Fournisseur)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiers">Tiers (Client / Fournisseur)</label>
                            <input type="text" name="tiers" id="tiers" class="form-control"
                                value="{{ old('tiers') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero_facture">Numéro de Facture</label>
                            <input type="text" name="numero_facture" id="numero_facture" class="form-control"
                                value="{{ old('numero_facture') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_facture">Date de Facture</label>
                            <input type="date" name="date_facture" id="date_facture" class="form-control"
                                value="{{ old('date_facture') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant">Montant (DH)</label>
                            <input type="number" step="0.01" name="montant" id="montant" class="form-control"
                                value="{{ old('montant') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description (Optionnel)</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="pieces_jointes">Pièces jointes (PDF, Image)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="pieces_jointes[]" class="custom-file-input" id="pieces_jointes"
                                multiple>
                            <label class="custom-file-label" for="pieces_jointes">Choisir des fichiers</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Vous pouvez sélectionner plusieurs fichiers.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('factures.index') }}" class="btn btn-default">Annuler</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Plugin for custom file input
        $(function() {
            // bsCustomFileInput.init(); // Requires bs-custom-file-input script if you want it
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
