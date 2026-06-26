@guest
    <script>
        window.location = "{{ route('/') }}";
    </script>
@endguest
@php
    $boutiques = \App\Models\Boutique::get();
    $magasins = \App\Models\Entrepot::get();
    $emailApi = '';
    $apiDataResponce = '';
    $configure = 'NON';
    $blueColor = '#075e98';
    $navbarColor = '#e0e3f5';
    $today = date('Y-m-d');
    $qte_alert = '';
    $admin = 'Admin';
    $superAdmin = 'Super Admin';
    $vendeur = 'Vendeur';
    $gestionaire = 'Gestionaire';
    $controlleur = 'Controlleur';
    $entreprise = 'App - Boutiqi';
    $logo = asset('backend/images/default_logo.jpeg');
    $setting =
        Auth::user()->roles == 'Super Admin'
        ? \App\Models\settings::first()
        : \App\Models\settings::find(Auth::user()->id_setting);

    if (!empty($setting)) {
        $configure = 'YES';
        $entreprise = $setting->app_name;
        $logo = $setting->logo;
        $title = $setting->title;
        $emailApi = $setting->email;
        $qte_alert = $setting->qte_alert;
        $blueColor = $setting->sidebar != null ? $setting->sidebar : $blueColor;
        $navbarColor = $setting->navbar != null ? $setting->navbar : $navbarColor;
    }

    //$response = Http::get('https://testapi.skillcodiing.com/api/sms/v1.0/balance/'. $emailApi);
    //$apiDataResponce=$response->json();
    $qte_alerts = App\Models\Produit::where('id_setting', auth()->user()->id_setting)
        ->where('quantite', '<=', $qte_alert)
        ->latest()
        ->limit(100)
        ->get();
    $expires = App\Models\Produit::where('id_setting', auth()->user()->id_setting)
        ->whereDate('date_expiration', '<=', $today)
        ->latest()
        ->limit(100)
        ->get();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Boutigi | {{ $entreprise }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('backend/dist/img/favicon.ico') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Theme style -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    @php
        echo "<style>
                                        .sidebar-dark-primary{
                                            background-color: {$blueColor}
                                        }
                                        .navbar-dark-primary{
                                            background-color: {$navbarColor};
                                        }
                                    </style>";
    @endphp
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light navbar-dark-primary">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-envelope"></i>
                        @if (!empty($apiDataResponce))
                            <span class="badge badge-danger navbar-badge">{{ $apiDataResponce['balance'] ?? '0' }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">Compte sms :
                            {{ $apiDataResponce['balance'] ?? '0' }}</span>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        @if (count($expires) > 0)
                            <span class="badge badge-danger navbar-badge">{{ count($expires) }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">{{ count($expires) }} - Alerte expiration</span>
                        @if (count($expires) > 0)
                            @foreach ($expires as $exp)
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-bell text-danger mr-2"></i> {{ $exp->quantite }} -
                                    {{ $exp->nom_produit }}
                                    <span class="float-right text-muted text-sm">Expiré :
                                        {{ date('d/m/Y', strtotime($exp->date_expiration)) }}</span>
                                </a>
                            @endforeach
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('produit.index') }}" class="dropdown-item dropdown-footer">Afficher
                            Stocks</a>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-shopping-basket"></i>
                        @if (count($qte_alerts) > 0)
                            <span class="badge badge-warning navbar-badge">{{ count($qte_alerts) }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">{{ count($qte_alerts) }} - Alerte Stock</span>
                        @if (count($qte_alerts) > 0)
                            @foreach ($qte_alerts as $qte_dt)
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-bell text-warning mr-2"></i> {{ $qte_dt->quantite }} qté(s)
                                    {{ $qte_dt->nom_produit }}
                                </a>
                            @endforeach
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('produit.index') }}" class="dropdown-item dropdown-footer">Afficher
                            Stocks</a>
                    </div>
                </li>

                <!-- end notification -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{ asset('backend/images/image.jpg') }}" alt="User image"
                            style="height: 25px; width: 25px; border-radius: 30px;"> <i class="far fa-right"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">{{ auth()->user()->roles }}</span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#printJour" href="#">
                            <i class="fas fa-print mr-2 text-primary"></i> Rapport du jour
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#printMois" href="#">
                            <i class="fas fa-print mr-2 text-primary"></i> Rapport du mois
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#printAnnee" href="#">
                            <i class="fas fa-print mr-2 text-primary"></i> Rapport du l'annee
                        </a>
                        @if (auth()->user()->roles === 'Super Admin')
                            <a class="dropdown-item" href="{{ route('minidashboard') }}">
                                <i class="fas fa-home mr-2 text-primary"></i> Mini Dashboard
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#editPassword" href="#">
                            <i class="fas fa-edit mr-2 text-primary"></i> Modifier Mot de passe
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item dropdown-footer" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>
                <!-- Navbar Search -->
            </ul>
        </nav>
        <!-- /.navbar -->


        <!-- /.modal -->

        <div class="modal fade" id="editPassword">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-head">
                        <h4 class="modal-title ">Modification de mot passe - {{ auth()->user()->name }} </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('users.update', [auth()->user()->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="types" value="USERS">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Ancien mot de passe</label>
                                        <input type="password" name="password_old"
                                            class="form-control @error('password_old') is-invalid @enderror"
                                            id="password_old" placeholder="">
                                        @error('password_old')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe </label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            placeholder="">
                                        @error('password')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Confirmer mot de passe </label>
                                        <input type="password" name="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" placeholder="">
                                        @error('password_confirmation')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default rounded-pill px-4"
                                data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Confirmer</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal -->

        <div class="modal fade" id="printJour">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-head">
                        <h4 class="modal-title ">Mon rapport du Jours</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('search.userRepport') }}" method="get">
                        @csrf
                        <input type="hidden" name="option" value="JOUR">
                        <input type="hidden" name="today" value="{{ date('Y-m-d') }}">

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Séléctionnez</label>
                                        <select name="op" class="form-control @error('op') is-invalid @enderror"
                                            required>
                                            <option value="">...</option>
                                            <option value="VENTE">VENTE</option>
                                            <option value="AVANCE">PAIEMENT AVANCE</option>
                                            <option value="CRÉANCE">CRÉANCE</option>
                                        </select>
                                        @error('op')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default rounded-pill px-4"
                                data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Télécharger</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal -->

        <div class="modal fade" id="printMois">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-head">
                        <h4 class="modal-title "> Rapport du {{ date('M') }} mois</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('search.userRepport') }}" method="get">
                        @csrf
                        <input type="hidden" name="option" value="MOIS">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Séléctionnez</label>
                                        <select name="op" class="form-control  @error('op') is-invalid @enderror"
                                            required>
                                            <option value="">...</option>
                                            <option value="VENTE">VENTE</option>
                                            <option value="AVANCE">PAIEMENT AVANCE</option>
                                            <option value="CRÉANCE">CRÉANCE</option>
                                        </select>
                                        @error('op')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default rounded-pill px-4"
                                data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Télécharger</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal -->

        <div class="modal fade" id="printAnnee">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-head">
                        <h4 class="modal-title ">Rapport de l'annee {{ date('Y') }}</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('search.userRepport') }}" method="get">
                        @csrf
                        <input type="hidden" name="option" value="ANNEE">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Séléctionnez</label>
                                        <select name="op" class="form-control  @error('op') is-invalid @enderror"
                                            required>
                                            <option value="">...</option>
                                            <option value="VENTE">VENTE</option>
                                            <option value="AVANCE">PAIEMENT AVANCE</option>
                                            <option value="CRÉANCE">CRÉANCE</option>
                                        </select>
                                        @error('op')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default rounded-pill px-4"
                                data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Télécharger</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" sty>
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <h4 class="text-left">
                    <img class="rounded-pill" src="{{ asset($logo) }}" alt="" width="55" height="55"> {{ $entreprise }}
                    </h5>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->


                <!-- SidebarSearch Form -->


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        @if ($configure == 'YES')
                            @if (auth()->user()->roles == $admin || auth()->user()->roles == $superAdmin || auth()->user()->roles == $controlleur)
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill-wave-alt"></i>
                                        <p>
                                            Vente
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('vente.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vente.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item d-none">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill-wave-alt"></i>
                                        <p>
                                            Vente Indirect
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('venteIndirects.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('venteIndirects.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item d-none">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Créances
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('dette.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('dette.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item d-none">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Paiement d'avance
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('paiementavances.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('paiementavances.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Service
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('services.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('services.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            E-Transfert
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('mobilemoney.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('mobilemoney.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list"></i>
                                        <p>
                                            Produit
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('stock.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('stock.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item {{ Auth::user()->roles == 'Super Admin' ? '' : 'd-none' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-shopping-basket"></i>
                                        <p>
                                            Point de vente
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">

                                        @if (Auth::user()->roles == 'Super Admin')
                                            <li class="nav-item {{ count($boutiques) == 10 ? 'd-none' : '' }}">
                                                <a href="{{ route('boutique.index') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Nouveau</p>
                                                </a>
                                            </li>

                                            @foreach ($boutiques as $boutique)
                                                <li class="nav-item">
                                                    <a href="{{ route('boutique.index', ['id' => $boutique->id]) }}" class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>{{ $boutique->nom_boutique }}</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                            {{-- @else
                                            @foreach ($boutiques as $boutique)
                                            @if (auth()->user()->id_boutigue == $boutique->id)
                                            <li class="nav-item">

                                                <a href="{{ route('boutique.index', ['id' => $boutique->id]) }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $boutique->nom_boutique }}</p>
                                                </a>
                                            </li>
                                            @endif
                                            @endforeach --}}
                                        @endif
                                    </ul>
                                </li>

                                <li
                                    class="nav-item {{ Route::is('produit.*') || Route::is('historyTransfert') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-shopping-basket"></i>
                                        <p>
                                            Entrepôts
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('produit.index') }}"
                                                class="nav-link {{ Route::is('produit.*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher entrepôts</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('historyTransfert') }}"
                                                class="nav-link {{ Route::is('historyTransfert') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-exchange-alt"></i>
                                                <p>
                                                    Histoiriques
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                    class="nav-item {{ Route::is('productBoutique.*') || Route::is('productBoutique.index') && request('id') == $m->id || Route::is('entrepot.index') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Magasin
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            @if (auth()->user()->roles === 'Super Admin')
                                                <a href="{{ route('entrepot.index') }}"
                                                    class="nav-link {{ Route::is('entrepot.index') || Route::is('entrepot.*') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Nouveau - magasin</p>
                                                </a>
                                                @foreach ($magasins as $m)
                                                    <a href="{{ route('productBoutique.index', ['id' => $m->id]) }}"
                                                        class="nav-link {{ Route::is('productBoutique.index') && request('id') == $m->id ? 'active' : '' }}">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>{{ $m->nom_entrepot }}</p>
                                                    </a>
                                                @endforeach
                                            @else
                                                @foreach ($magasins as $m)
                                                    @if (auth()->user()->id_boutigue == $m->id_boutique)
                                                        <a href="{{ route('productBoutique.index', ['id' => $m->id]) }}"
                                                            class="nav-link {{Route::is('productBoutique.index') && request('id') == $m->id ? 'active' : '' }}">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>{{ $m->nom_entrepot }}</p>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-solid fa-users"></i>
                                        <p>
                                            Client
                                            <i class="right fas fa-angle-left"></i>

                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('client.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('client.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>
                                            Fournisseur
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('fournisseur.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('fournisseur.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list"></i>
                                        <p>
                                            Catégorie
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('categorie.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('categorie.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @if (auth()->user()->id == '1')
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon fas fa-list"></i>
                                            <p>
                                                Sous Catégorie
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{ route('type.index') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Afficher</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('type.create') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Ajouter</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-barcode"></i>
                                        <p>
                                            Code barre
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('barcode.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('barcode.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-chart-pie"></i>
                                        <p>
                                            Rapport
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('venteCreate') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Vente</p>
                                            </a>
                                        </li>
                                        <li class="nav-item d-none">
                                            <a href="{{ route('detteCreate') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Créances</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('gestions.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Entrer / Sortir </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>
                                            Employé
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('employes.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('employes.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Salaire
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('salaires.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('salaires.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        <p>
                                            Dépense
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('depenses.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('depenses.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @if (auth()->user()->roles == $admin)
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon fas fa-user"></i>
                                            <p>
                                                Compte Bancaire
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{ route('comptes.index') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Afficher</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('comptes.create') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Ajouter</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-exchange-alt"></i>
                                        <p>
                                            Opération Bancaire
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('banks.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('banks.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-exchange-alt"></i>
                                        <p>
                                            Les Commandes
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('commande.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Afficher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('commande.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>
                                            Caisses
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('caisse.afficher') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Caisse globale</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            @endif


                        @endif

                        @if (auth()->user()->id == '1')
                            <li class="nav-item {{ Route::is('settings.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Configuration
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('settings.index') }}"
                                            class="nav-link {{ Route::is('settings.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Afficher</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('settings.create') }}"
                                            class="nav-link {{ Route::is('settings.create') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ajouter</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if ($configure == 'YES')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                        Compte d'utilisateur
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Afficher</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('users.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ajouter</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Formation App Boutigi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="https://www.youtube.com/watch?v=X3QFahkPFkM&list=PLIaE7otT8WwKwG0Brkmarva4iBWC9bGMd"
                                            class="nav-link" target="_blank">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Formez vous sur youtube</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">

                <!-- Default box -->
                <div class="col-md-12 mt-2">

                    @if (session('succes'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Succès</h5>
                            {{ session('succes') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Erreur</h5>
                            {{ session('error') }}
                        </div>
                    @elseif(session('info'))
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Information</h5>
                            {!! session('info') !!}
                        </div>
                    @elseif(session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i>Fin d'abonnement</h5>
                            {!! session('warning') !!}
                        </div>
                    @endif

                    @yield('main')

                </div>


            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version 3.0</b>
            </div>
            <strong>Copyright &copy; SKill Codiing </strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Typeahead cdn -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('backend/dist/js/demo.js') }}"></script>
    <script src="{{ asset('backend/dist/js/custom.js') }}"></script>
    <!-- Page specific script -->
    @yield('scripts')
    <script type="text/javascript">
        /*
                                                                              var route = "{{ url('autocomplete-search') }}";
        $('#search').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    var products = $.map(data, function (item) {
                        return item.nom_produit;
                    });

                    return process(products);
                });
            },
            matcher: function (item) {
                if (typeof item !== 'string') {
                    return false;
                }
                return ~item.toLowerCase().indexOf(this.query.toLowerCase());
            },
            updater: function (item) {
                console.log(item);
                // set the value of the input to the selected item
                this.$element.val(item.nom_produit);

                // set the value of another input to a property of the selected item
                $('#prix').val(item.prix_vente_unitaire);

                // return the selected item to update the input value
                return item.nom_produit;
            }
        }); */
        // Define the URL for the autocomplete search

        var route = "{{ url('autocomplete-search') }}";
        $('#search').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    var products = $.map(data, function (item) {
                        return item.nom_produit;
                    });

                    return process(products);
                });
            },
            matcher: function (item) {
                if (typeof item !== 'string') {
                    return false;
                }
                return ~item.toLowerCase().indexOf(this.query.toLowerCase());
            },
            updater: function (item) {
                if (typeof item === "string") {
                    $('#search').val(item);
                } else if (typeof item === "object" && item.nom_produit) {
                    $('#nom_produit').val(item.nom_produit);
                    $('#prix').val(item.id); // update the value of the second input field
                }
                return item;
            }
        });
    </script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })


            $('#productId').DataTable({
                order: [
                    [1, 'desc']
                ],
                paging: false,
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#example1').DataTable({
                order: [
                    [0, 'asc']
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#example3').DataTable({
                order: [
                    [0, 'asc']
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                // buttons: ["excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('.example3').DataTable({
                order: [
                    [0, 'asc']
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                // buttons: ["excel", "pdf", "print", "colvis"]
            });


            $('#example2').DataTable({
                paging: true,
                lengthChange: false,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        });
    </script>

</body>

</html>