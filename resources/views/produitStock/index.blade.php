@extends('layout.main')
@section('main')
    @php
        $cc = 'Fcfa';
        $admin = 'Admin';
        $superAdmin = 'Super Admin';
        $vendeur = 'Vendeur';
        $gestionaire = 'Gestionaire';
        $controlleur = 'Controlleur';
        $today = date('Y-m-d');
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des produits
                @if ($magasin != null && auth()->user()->roles == 'Super Admin')
                    de <span class="bg-success ml-2 p-1">
                        {{  $magasin->nom_entrepot }}
                    </span>
                    @else
                    <span></span>
                @endif
            </h3>
            @if (auth()->user()->roles != $vendeur)
                <a class="btn btn-outline-primary rounded-pill float-right d-none" href="{{ route('produit.create') }}">
                    <i class="fa fa-plus"></i> Ajouter
                </a>
            @endif
            <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()"
                id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
            <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()"
                id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
        </div>
        <!-- -->
        <div id="form" class="mb-5 d-none">
            <form action="{{ route('seach.Item') }}" method="get" class="form-control">
                @csrf
                <input type="hidden" name="types" id="" value="PRODUIT">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date debut</label>
                            <input type="date" name="dateDebut"
                                class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
                            @error('dateDebut')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date fin</label>
                            <input type="date" name="dateFin"
                                class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
                            @error('dateFin')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant" class="form-label">Produit</label>
                            <select name="produit" id="produit"
                                class="form-control @error('produit') is-invalid @enderror">
                                <option value="">...</option>
                                @if (count($datas) > 0)
                                    @foreach ($datas as $data)
                                        <option value="{{ $data->id }}">{{ $data->nom_produit }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('produit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="gap-2 d-md-flex d-md-block justify-content-center">
                    <button type="submit" class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
                    <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
                </div>
            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body mt-5">
            <a href="{{ route('productBoutique.index') }}" class="btn btn-outline-success mb-2 px-2 ">Total qté en stock :
                {{ $totalQ }}
            </a>
            <a href="{{ route('productBoutique.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total Achat :
                {{ number_format($totalA) }}{{ $cc }}</a>
            <a href="{{ route('productBoutique.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total en Détail :
                {{ number_format($totalAu) }}{{ $cc }}</a>
            <a href="{{ route('productBoutique.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total en gros :
                {{ number_format($totalG) }}{{ $cc }}</a>
            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom Produit</th>
                        <th>Qté</th>
                        @if (auth()->user()->roles != $vendeur)
                            <th>Prix Achat</th>
                        @endif
                        <th>Prix Détail</th>
                        <th>Total Détail</th>
                        <th>Prix Gros</th>
                        <th>Total Gros</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->created_at->format('d/m/Y') }}</td>
                                <td class="@if (!empty($data->date_expiration) && $data->date_expiration <= $today) bg-danger @endif">
                                    {{ $data->ShowProdName($data->id_prod) }}
                                </td>
                                <td class="@if ($data->quantite <= $qte_alert['0']) bg-warning @endif">{{ $data->quantite }}</td>
                                @if (auth()->user()->roles != $vendeur)
                                    <td>{{ number_format($data->prix_achat) }}{{ $cc }}</td>
                                @endif
                                <td>{{ number_format($data->prix_vente_unitaire) }}{{ $cc }}</td>
                                <td>{{ number_format($data->prix_vente_unitaire * $data->quantite) }}{{ $cc }}
                                </td>
                                <td>{{ number_format($data->prix_vente_en_gros) }}{{ $cc }}</td>
                                <td>{{ number_format($data->prix_vente_en_gros * $data->quantite) }}{{ $cc }}
                                </td>
                                <td>
                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#bene{{ $data->id }}" href="#">
                                        <i class="fas fa-eye px-1 text-info"></i>
                                    </a>
                                    <!-- /.modal -->
                                    <div class="modal fade" id="bene{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Benefices (En Gros et Detaile)</h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Total benefice en gros :
                                                        {{ number_format($data->prix_vente_en_gros * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                    </h6>
                                                    <h6>Total benefice en detaile :
                                                        {{ number_format($data->prix_vente_unitaire * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                    </h6>
                                                    <h6>Date d'expiration : {{ $data->date_expiration }}</h6>
                                                    @if (!empty($data->code_barre))
                                                        <p height="50" width="50">
                                                            {!! DNS1D::getBarcodeSVG("$data->code_barre", 'C39', 1, 100, true) !!}
                                                        </p>
                                                    @else
                                                        <h4 class="text-success text-center">Pas de code bar</h4>
                                                    @endif
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default rounded-pill"
                                                        data-dismiss="modal">Fermer</button>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </div>
                                    <!-- /.modal -->

                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#annuler{{ $data->id }}" href="#"
                                        class="d-none">
                                        <i class="fas fa-eye px-1 text-info"></i>
                                    </a>
                                    <!-- /.modal -->
                                    <div class="modal fade" id="annuler{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Annuler une commande</h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Total benefice en gros :
                                                        {{ number_format($data->prix_vente_en_gros * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                    </h6>
                                                    <h6>Total benefice en detaile :
                                                        {{ number_format($data->prix_vente_unitaire * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                    </h6>
                                                    <h6>Date d'expiration : {{ $data->date_expiration }}</h6>
                                                    @if (!empty($data->code_barre))
                                                        <p height="50" width="50">
                                                            {!! DNS1D::getBarcodeSVG("$data->code_barre", 'C39', 1, 100, true) !!}
                                                        </p>
                                                    @else
                                                        <h4 class="text-success text-center">Pas de code bar</h4>
                                                    @endif
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default rounded-pill"
                                                        data-dismiss="modal">Fermer</button>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </div>
                                    <!-- /.modal -->
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
