@auth
    @extends('layout.main')
    @section('main')
        @php
            $currency = ' F cfa';
        @endphp

        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            Tableau de bord
                            @if (session()->has('selected_boutique_name'))
                                <small class="text-muted">— {{ session('selected_boutique_name') }}</small>
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- ==================== DAILY SECTION ==================== -->
                <h3 class="mt-2 mb-3">Aujourd'hui</h3>

                <div class="row">
                    <!-- Total Ventes du jour -->
                    <div class="col-md-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-file-invoice-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Ventes (dû)</span>
                                <span class="info-box-number">{{ number_format($todayDu, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Stock du jour -->
                    <div class="col-md-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-shopping-basket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock du jour</span>
                                <span
                                    class="info-box-number">{{ number_format($today_Stock, 0, ",", " ") }}{{ $currency }}</span>
                                <span class="progress-description">Qté(s) : {{ $today_S }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily details -->
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-tags"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Réduction</span>
                                <span
                                    class="info-box-number">{{ number_format($todayReduction, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-calculator"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net à payer</span>
                                <span
                                    class="info-box-number">{{ number_format($todayNetAPayer, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total payé</span>
                                <span class="info-box-number">{{ number_format($todayPaye, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reste à payer</span>
                                <span
                                    class="info-box-number">{{ number_format($todayRestant - $todayReduction, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily profit -->
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-dark"><i class="fas fa-coins"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bénéfice du jour</span>
                                <span
                                    class="info-box-number">{{ number_format($todayBenefice, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <a data-toggle="modal" data-target="#venteToday" class="btn btn-outline-success btn-block mt-3">
                            <i class="fas fa-print"></i> Imprimer rapport journalier
                        </a>
                    </div>
                </div>

                <!-- Modal impression jour -->
                <div class="modal fade" id="venteToday">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header modal-head">
                                <h4 class="modal-title">Imprission</h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('seach.statistics') }}" method="get">
                                @csrf
                                <input type="hidden" name="types" value="VENTES">
                                <input type="hidden" name="option" value="PRINT">
                                <input type="hidden" name="dateDebut" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="dateFin" value="{{ date('Y-m-d') }}">
                                <div class="modal-body">
                                    <p>Voulez-vous imprimer le rapport de vente de jour?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default rounded-pill"
                                        data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ==================== MONTHLY SECTION ==================== -->
                <h3 class="mt-4 mb-3">Ce mois</h3>

                <div class="row">
                    <!-- Total Ventes du mois -->
                    <div class="col-md-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-file-invoice-dollar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Ventes (dû)</span>
                                <span class="info-box-number">{{ number_format($monthDu, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Stock du mois -->
                    <div class="col-md-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-shopping-basket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock du mois</span>
                                <span class="info-box-number">{{ number_format($prodTotal, 0, ",", " ") }}{{ $currency }}</span>
                                <span class="progress-description">Qté(s) : {{ $prodT }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly details -->
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-tags"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Réduction</span>
                                <span
                                    class="info-box-number">{{ number_format($monthReduction, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-calculator"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net à payer</span>
                                <span
                                    class="info-box-number">{{ number_format($monthNetAPayer, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total payé</span>
                                <span class="info-box-number">{{ number_format($monthPaye, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reste à payer</span>
                                <span
                                    class="info-box-number">{{ number_format($monthRestant - $monthReduction, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly profit -->
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-dark"><i class="fas fa-coins"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bénéfice du mois</span>
                                <span
                                    class="info-box-number">{{ number_format($monthBenefice, 0, ",", " ") }}{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <a data-toggle="modal" data-target="#venteMois" class="btn btn-outline-success btn-block mt-3">
                            <i class="fas fa-print"></i> Imprimer rapport mensuel
                        </a>
                    </div>
                </div>

                <!-- Modal impression mois -->
                <div class="modal fade" id="venteMois">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header modal-head">
                                <h4 class="modal-title">Imprission</h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('monthly') }}" method="get">
                                @csrf
                                <input type="hidden" name="op" value="VENTE">
                                <div class="modal-body text-dark">
                                    <p>Voulez-vous imprimer le rapport de ce mois?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default rounded-pill"
                                        data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ==================== DERNIÈRES VENTES PAR BOUTIQUE ==================== -->
                <h3 class="mt-4 mb-3">Dernières ventes par boutique</h3>
                <div class="row">
                    @foreach ($boutiqueSales as $bs)
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $bs['boutique']->nom_boutique }}</h3>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @forelse ($bs['ventes'] as $vente)
                                        <div class="text-center bg-primary p-2 rounded shadow-sm mt-1 mb-1">
                                            Vente N°: {{ $vente->num_vente }}
                                        </div>
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Produit</th>
                                                    <th>Prix</th>
                                                    <th>Qté</th>
                                                    <th>Montant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $somme = 0; @endphp
                                                @foreach ($vente->items as $item)
                                                    @php
                                                        $product = \App\Models\ProductBoutigue::where('id_prod', $item->id_prod)
                                                            ->where('id_boutique', $bs['boutique']->id)
                                                            ->first();
                                                        $prodName = $product->produit->nom ?? 'Produit #' . $item->id_prod;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $prodName }}</td>
                                                        <td>{{ number_format($item->prix, 0, ",", " ") }}{{ $currency }}</td>
                                                        <td>{{ $item->quantite }}</td>
                                                        <td>{{ number_format($item->montant, 0, ",", " ") }}{{ $currency }}</td>
                                                    </tr>
                                                    @php $somme += $item->montant; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @empty
                                        <p class="text-center">Aucune vente récente.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ==================== CHARTS ==================== -->
                <div class="row mt-4">
                    <section class="col-lg-6 connectedSortable">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Statistiques</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6 connectedSortable">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Vente des 2 derniers mois</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </section>
    @endsection

    @section('scripts')
        <script>
            function monthName(v) {
                const months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
                return months[(v + 12) % 12];
            }
            function thisMonth() { return new Date().getMonth(); }
            function lastMonth() { return new Date().getMonth() - 1; }

            $(function () {
                // BAR CHART
                new Chart($('#barChart').get(0).getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: [monthName(lastMonth()), monthName(thisMonth())],
                        datasets: [
                            { label: 'Stocks', backgroundColor: '#0dcaf0', data: [{{ $last_monthStocks }}, {{ $today_Stock }}] },
                            { label: 'Ventes', backgroundColor: '#198754', data: [{{ $last_monthVentes }}, {{ $monthDu }}] }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });

                // PIE CHART
                new Chart($('#pieChart').get(0).getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: ['Stocks', 'Ventes'],
                        datasets: [{ data: [{{ $prodTotal }}, {{ $monthDu }}], backgroundColor: ['#0dcaf0', '#00a65a'] }]
                    },
                    options: { maintainAspectRatio: false, responsive: true }
                });
            });
        </script>
    @endsection
@endauth