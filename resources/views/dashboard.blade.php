@extends('layouts.app')

@section('title', 'Tableau de bord Général')

@section('content')
    <!-- Global Stats Row -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_invoices'] }}</h3>
                    <p>Total Factures</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['total_actif'], 2) }} <sup style="font-size: 20px">DH</sup></h3>
                    <p>Global Actif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($stats['total_passif'], 2) }} <sup style="font-size: 20px">DH</sup></h3>
                    <p>Global Passif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['companies_count'] }}</h3>
                    <p>Sociétés</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Section Alertes / Activités Récentes (Gauge 4/12) -->
        <div class="col-md-4">
            <div class="card card-default" style="height: 100%;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i>
                        Activités Récentes
                    </h3>
                </div>
                <div class="card-body" style="overflow-y: auto; max-height: 400px;">
                    @forelse($recentInvoices as $invoice)
                        <div class="callout callout-{{ $invoice->type == 'actif' ? 'success' : 'danger' }}">
                            <h5>{{ $invoice->entreprise->nom ?? 'N/A' }}</h5>
                            <p>
                                Nouvelle facture <strong>{{ $invoice->type }}</strong> de
                                {{ number_format($invoice->montant, 2) }} DH
                                <br>
                                <small class="text-muted"><i class="far fa-clock"></i> Ajoutée
                                    {{ $invoice->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    @empty
                        <p>Aucune activité récente.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section Graphique (Gauge 8/12) - Moved here -->
        <div class="col-md-8">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h3 class="card-title">Évolution Recettes vs Dépenses (6 derniers mois)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart"
                            style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Détails par Entreprise - Moved below -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Performance par Société</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row p-3">
                        @foreach ($companiesStats as $company)
                            @php
                                $solde = ($company->total_actif ?? 0) - ($company->total_passif ?? 0);
                                $color = $solde >= 0 ? 'success' : 'danger';
                            @endphp
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title m-0 text-bold text-dark">{{ $company->nom }}</h5>
                                        <div class="card-tools">
                                            <span class="badge badge-primary">{{ $company->factures_count }}
                                                Factures</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-success"><i class="fas fa-arrow-up"></i> Actif:</span>
                                            <span
                                                class="font-weight-bold">{{ number_format($company->total_actif ?? 0, 2) }}
                                                DH</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-danger"><i class="fas fa-arrow-down"></i> Passif:</span>
                                            <span
                                                class="font-weight-bold">{{ number_format($company->total_passif ?? 0, 2) }}
                                                DH</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-dark font-weight-bold">Solde:</span>
                                            <span class="badge badge-{{ $color }}" style="font-size: 1rem;">
                                                {{ number_format($solde, 2) }} DH
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="{{ route('factures.index', ['entreprise_id' => $company->id]) }}"
                                            class="small-box-footer">
                                            Voir détails <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function() {
            var areaChartData = {
                labels: @json($chartData['labels']),
                datasets: [{
                        label: 'Actif (Recettes)',
                        backgroundColor: 'rgba(40, 167, 69, 0.9)',
                        borderColor: 'rgba(40, 167, 69, 0.8)',
                        pointRadius: false,
                        pointColor: 'rgba(40, 167, 69, 1)',
                        pointStrokeColor: 'rgba(40, 167, 69, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(40, 167, 69, 1)',
                        data: @json($chartData['actif'])
                    },
                    {
                        label: 'Passif (Dépenses)',
                        backgroundColor: 'rgba(220, 53, 69, 0.9)',
                        borderColor: 'rgba(220, 53, 69, 0.8)',
                        pointRadius: false,
                        pointColor: 'rgba(220, 53, 69, 1)',
                        pointStrokeColor: 'rgba(220, 53, 69, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220, 53, 69, 1)',
                        data: @json($chartData['passif'])
                    },
                ]
            }

            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })
    </script>
@endpush
