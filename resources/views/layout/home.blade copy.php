@auth
    @extends('layout.main')
    @section('main')
        @php
            $currency = ' F cfa';
            $totalBeneficeVenteJour = 0;
            $totalBeneficeVenteMois = 0;
        @endphp
        <!-- Content Header (Page header) -->
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
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="nav-icon fas fa-money-bill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vente du Jours</span>
                                <span class="info-box-number">{{ number_format($today_Vente) }}{{ $currency }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <span>Qté(s) : {{ $today_V }} </span> <br>
                                    <span class="d-none">Bénéfice :
                                        @foreach ($today_dataVBs as $today_dataVB)
                                            @php
                                                //$today_dataVB->CalculerBenefice($today_dataVB->id)
                                                $totalBeneficeVenteJour +=
                                                    (int) $today_dataVB->ShowPriceAchat($today_dataVB->id_prod) *
                                                    (int) $today_dataVB->quantite;
                                            @endphp
                                        @endforeach

                                        {{ number_format($today_Vente - $totalBeneficeVenteJour - (int) $venteReductionJours) }}{{ $currency }}
                                    </span> <br>

                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#venteToday" href="#"
                                        class="small-box-footer float-right">
                                        Imprimer <i class="fas fa-print px-1"></i>
                                    </a>

                                    <!-- /.modal -->

                                    <div class="modal fade" id="venteToday">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Imprission </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('seach.statistics') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="types" id="" value="VENTES">
                                                    <input type="hidden" name="option" id="" value="PRINT">
                                                    <input type="hidden" name="dateDebut" value="{{ date('Y-m-d') }}">
                                                    <input type="hidden" name="dateFin" value="{{ date('Y-m-d') }}">

                                                    <div class="modal-body">
                                                        <p>
                                                            Voulez-vous imprimer le rapport de vente de jour?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-success rounded-pill">Confirmer</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->



                                </span>
                            </div>
                        </div>

                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="nav-icon fas fa-shopping-basket"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock du Jours</span>
                                <span class="info-box-number">{{ number_format($today_Stock) }}{{ $currency }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <span>Qté(s) : {{ $today_S }} </span>
                                    <br><br>
                                    <a href="{{ route('produit.index') }}" class="small-box-footer float-right"> voir <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="nav-icon fas fa-donate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Créances du Jours</span>
                                <span class="info-box-number">{{ number_format($today_Dette) }}{{ $currency }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <span>Qté(s) : {{ $today_D }} </span><br>
                                    <span></span> <br>

                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#detteToday" href="#"
                                        class="small-box-footer float-right">
                                        Imprimer <i class="fas fa-print px-1"></i>
                                    </a>

                                    <!-- /.modal -->
                                    <div class="modal fade" id="detteToday">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Imprission </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('seach.statistics') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="types" id="" value="DETTES">
                                                    <input type="hidden" name="option" id="" value="PRINT">
                                                    <input type="hidden" name="dateDebut" value="{{ date('Y-m-d') }}">
                                                    <input type="hidden" name="dateFin" value="{{ date('Y-m-d') }}">

                                                    <div class="modal-body">
                                                        <p>
                                                            Voulez-vous imprimer le rapport de dette de jour?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-success rounded-pill">Confirmer</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                </span>
                            </div>
                        </div>

                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="nav-icon fas fa-exchange-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Avance du Jours</span>
                                <span class="info-box-number">{{ number_format($today_Paiement) }}{{ $currency }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <span>Qté(s) : {{ $today_P }} </span><br>
                                    <span></span> <br>

                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#vanteToday" href="#"
                                        class="small-box-footer float-right">
                                        Imprimer <i class="fas fa-print px-1"></i>
                                    </a>

                                    <!-- /.modal -->
                                    <div class="modal fade" id="vanteToday">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Imprission </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('monthly') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="op" id="" value="AVANCE_JOUR">
                                                    <input type="hidden" name="option" id="" value="PRINT">
                                                    <input type="hidden" name="startFrom" value="{{ date('Y-m-d') }}">
                                                    <input type="hidden" name="endAt" value="{{ date('Y-m-d') }}">

                                                    <div class="modal-body">
                                                        <p>
                                                            Voulez-vous imprimer le rapport de paiement d'avance du jour?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-success rounded-pill">Confirmer</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                </span>


                            </div>
                        </div>

                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h4>{{ number_format($this_monthVentes ?? '0') }}{{ $currency }}</h4>
                                <p>Vente du mois</p>
                                <span>Qté(s) : {{ $venteT ?? '0' }} </span> <br>
                                <span class="d-none">Bénéfice :
                                    @foreach ($venteMonths as $venteMonth)
                                        @php
                                            $totalBeneficeVenteMois +=
                                                (int) $venteMonth->ShowPriceAchat($venteMonth->id_prod) *
                                                (int) $venteMonth->quantite;
                                        @endphp
                                    @endforeach
                                    {{ number_format($venteTotal - $totalBeneficeVenteMois - $venteReductionMois) }}
                                    {{ $currency }}
                                </span> <br>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-money-bill"></i>
                            </div>

                            <!-- activer -->
                            <a data-toggle="modal" data-target="#venteMois" href="#" class="small-box-footer">
                                Imprimer <i class="fas fa-print px-1"></i>
                            </a>

                            <!-- /.modal -->
                            <div class="modal fade" id="venteMois">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header modal-head">
                                            <h4 class="modal-title">Imprission </h4>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('monthly') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="op" value="VENTE">
                                            <div class="modal-body text-dark">
                                                <p>
                                                    Voulez-vous imprimer le rapport de ce mois?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default rounded-pill"
                                                    data-dismiss="modal">Fermer</button>
                                                <button type="submit"
                                                    class="btn btn-outline-success rounded-pill">Confirmer</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h4>{{ number_format($prodTotal ?? '0') }}{{ $currency }}</h4>
                                <p>Stock du mois</p>
                                <span>Qté(s) : {{ $prodT ?? '0' }} </span><br><br>
                            </div>

                            <div class="icon">
                                <i class="nav-icon fas fa-shopping-basket"></i>
                            </div>
                            <a href="{{ route('produit.create') }}" class="small-box-footer"> voir <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h4>{{ number_format($this_monthDettes ?? '0') }}{{ $currency }}</h4>
                                <p>Créances du mois </p>
                                <span>Qté(s) : {{ $detteQteMont ?? '0' }} </span> <br>
                                <span></span> <br>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-donate"></i>
                            </div>
                            <!-- activer -->
                            <a data-toggle="modal" data-target="#detteMois" href="#" class="small-box-footer">
                                Imprimer <i class="fas fa-print px-1"></i>
                            </a>

                            <!-- /.modal -->
                            <div class="modal fade" id="detteMois">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header modal-head">
                                            <h4 class="modal-title">Imprission </h4>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('monthly') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="op" value="DETTE">
                                            <div class="modal-body text-dark">
                                                <p>
                                                    Voulez-vous imprimer le rapport de ce mois?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default rounded-pill"
                                                    data-dismiss="modal">Fermer</button>
                                                <button type="submit"
                                                    class="btn btn-outline-success rounded-pill">Confirmer</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h4>{{ number_format($paiementTotal ?? '0') }}{{ $currency }}</h4>
                                <p>Paiement d'avance du mois </p>
                                <span>Qté(s) : {{ $paiementT ?? '0' }} </span> <br>
                                <br>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-exchange-alt"></i>
                            </div>
                            <!-- activer -->
                            <a data-toggle="modal" data-target="#paiementMois" href="#" class="small-box-footer">
                                Imprimer <i class="fas fa-print px-1"></i>
                            </a>

                            <!-- /.modal -->
                            <div class="modal fade" id="paiementMois">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header modal-head">
                                            <h4 class="modal-title">Imprission </h4>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('monthly') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="op" value="AVANCE">
                                            <div class="modal-body text-dark">
                                                <p>
                                                    Voulez-vous imprimer le rapport de ce mois?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default rounded-pill"
                                                    data-dismiss="modal">Fermer</button>
                                                <button type="submit"
                                                    class="btn btn-outline-success rounded-pill">Confirmer</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-6 connectedSortable">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Les 5 dernières ventes</h3>
                                <div class="card-tools d-none">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body" style="height: 290px; overflow-y: auto; overflow-x: hidden;">
                                @if (count($dataSts) > 0)
                                    @foreach ($dataSts as $data_item)
                                    <div class="text-center bg-primary p-2 rounded shadow-sm mt-1 mb-1">
                                      {{ 'Vente N°: '.$data_item->num_vente }}
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
                                                @php
                                                    $somme = 0;
                                                @endphp
                                                @foreach ($data_item->detail_ventes($data_item->id) as $data)
                                                    <tr>
                                                        <td>{{ $data->ShowProdNameVente($data->id_prod) }}</td>
                                                        <td>{{ number_format($data->prix) }}{{ $currency }}</td>
                                                        <td>{{ $data->quantite }}</td>
                                                        <td>{{ number_format($data->montant) }}{{ $currency }}</td>
                                                    </tr>
                                                    @php
                                                        $somme += $data->montant;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                @endif

                                {{-- <h5 class="float-right">Total : {{ number_format($somme) }}{{ $currency }}</h5> --}}
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-6 connectedSortable">

                        <!-- PIE CHART -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Statistiques</h3>

                                <div class="card-tools d-none">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                </div>
                <!-- /.card -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- BAR CHART -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Vente des 2 derniers mois</h3>

                                <div class="card-tools d-none">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>

        </section>
    @endsection

    @section('scripts')
        <script>
            function monthName(value) {
                var monthname = "Janvier";
                if (value <= -1) {
                    monthname = "Décembre";
                } else if (value === 0) {
                    monthname = "Janvier";
                } else if (value === 1) {
                    monthname = "Février";
                } else if (value === 2) {
                    monthname = "Mars";
                } else if (value === 3) {
                    monthname = "Avril";
                } else if (value === 4) {
                    monthname = "Mai";
                } else if (value === 5) {
                    monthname = "Juin";
                } else if (value === 6) {
                    monthname = "Juillet";
                } else if (value === 7) {
                    monthname = "Août";
                } else if (value === 8) {
                    monthname = "Septembre";
                } else if (value === 9) {
                    monthname = "Octobre";
                } else if (value == 10) {
                    monthname = "Novembre";
                } else if (value == 11) {
                    monthname = "Décembre";
                }
                return monthname;
            }

            function thisMonth() {
                const d = new Date();
                return d.getMonth();
            }

            function lastMonth() {
                const d = new Date();
                return d.getMonth() - 1;

            }

            function monthBeforeLastMonth() {
                const d = new Date();
                return d.getMonth() - 2;
            }

            $(function() {
                var areaChartData = {
                    labels: [monthName(lastMonth()), monthName(thisMonth())],
                    datasets: [{
                            label: 'Stocks',
                            backgroundColor: '#0dcaf0',
                            borderColor: '#0dcaf0',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: [{{ $last_monthStocks }}, {{ $this_monthStocks }}]
                        },
                        {
                            label: 'Créances',
                            backgroundColor: '#dc3545',
                            borderColor: '#dc3545',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: [{{ $last_monthDettes }}, {{ $this_monthDettes }}]
                        },
                        {
                            label: 'Ventes',
                            backgroundColor: '#198754',
                            borderColor: '#198754',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: [{{ $last_monthVentes }}, {{ $this_monthVentes }}]
                        },
                        {
                            label: "Paiement d'avance",
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: [{{ $last_monthPaiement }}, {{ $this_monthPaiement }}]
                        },
                    ]
                }

                //-------------
                //- BAR CHART -
                //-------------
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

                //Pie chat
                var donutData = {
                    labels: [
                        'Stocks',
                        'Ventes',
                        'Créances',
                        "Paiement d'avance",
                    ],
                    datasets: [{
                        data: [
                            {{ $prodTotal ?? 0 }},
                            {{ $venteTotal ?? 0 }},
                            {{ $this_monthDettes ?? 0 }},
                            {{ $paiementTotal ?? 0 }}
                        ],
                        backgroundColor: ['#0dcaf0', '#00a65a', '#dc3545', '#007bff'],
                    }]
                }
                //-------------
                //- PIE CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                var pieData = donutData;
                var pieOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                })

            })
        </script>
    @endsection
@endauth
