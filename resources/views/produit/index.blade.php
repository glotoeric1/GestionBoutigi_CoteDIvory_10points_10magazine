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
            <h3 class="card-title">
                Liste des produits dans entreprôt
                {{-- <span class="bg-success p-1"> 
        [ {{ $entrepot_name->nom_entrepot }} ]
        </span> --}}
            </h3>
            <div class="card-tools">
                @if (auth()->user()->roles != $vendeur)
                    <a class="btn btn-outline-primary rounded-pill d-none" href="{{ route('produit.create') }}"> <i
                            class="fa fa-plus"></i> Ajouter</a>
                @endif
                <a href="#" class="btn btn-outline-primary rounded-pill mx-2" onclick="showForm()" id="btnOpen"> <i
                        class="fas fa-search"></i> Recherche Avancé </a>
                <a href="#" class="btn btn-outline-danger rounded-pill  mx-2 d-none " onclick="closeForm()"
                    id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
            </div>
        </div>
        <!-- -->
        <div id="form" class="mb-3 d-none">
            <form action="{{ route('produit.search') }}" method="get" class="border p-3">
                @csrf
                <input type="hidden" name="types" value="PRODUIT">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="produit" class="form-label">Produit</label>
                            <select name="produit" id="produit"
                                class="form-control @error('produit') is-invalid @enderror">
                                <option value="">...</option>
                                @if (count($produits) > 0)
                                    @foreach ($produits as $data)
                                        <option value="{{ $data->id }}">{{ $data->libelle }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('produit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_categorie" class="form-label">Catégorie</label>
                            <select name="id_categorie" id="id_categorie"
                                class="form-control @error('id_categorie') is-invalid @enderror">
                                <option value="">...</option>
                                @if (count($categories) > 0)
                                    @foreach ($categories as $data)
                                        <option value="{{ $data->id }}">{{ $data->nom_categorie }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('id_categorie')
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
        <div class="card-body mt-2">
            <a href="{{ route('produit.index') }}" class="btn btn-outline-success mb-2 px-2 ">Total qté en stock :
                {{ $totalQ }}
            </a>
            <a href="{{ route('produit.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total Achat :
                {{ number_format($totalA) }}{{ $cc }}</a>
            <a href="{{ route('produit.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total en Détail :
                {{ number_format($totalAu) }}{{ $cc }}</a>
            <a href="{{ route('produit.index') }}" class="btn btn-outline-success mb-2 px-2 mx-2">Total en gros :
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
                                <td>
                                    {{ $data->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('produit.show', $data->id) }}" class="fw-bold">
                                        <div
                                            class="px-3 rounded {{ $data->date_expiration != '' && $data->date_expiration <= $today ? 'bg-danger' : 'bg-default text-primary' }}">
                                            {{ $data->ShowProdName($data->id_prod) }}
                                            <i class="fa fa-cog fa-spin float-right p-1"></i>
                                        </div>
                                    </a>
                                </td>
                                <td class="@if ($data->quantite <= $qte_alert) bg-warning @endif">
                                    {{ $data->quantite }}
                                </td>
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
                                                <form action="{{ route('codebar.add') }}" method="post">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <h6>Total benefice en gros :
                                                            {{ number_format($data->prix_vente_en_gros * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                        </h6>
                                                        <h6>Total benefice en detaile :
                                                            {{ number_format($data->prix_vente_unitaire * $data->quantite - $data->prix_achat * $data->quantite) }}{{ $cc }}
                                                        </h6>
                                                        <h6>Date d'expiration :
                                                            {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}
                                                        </h6>
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
                                                </form>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </div>
                                    <!-- /.modal -->

                                    <a href="{{ route('produit.edit', [$data->id]) }}">
                                        <i class="fas fa-edit px-1 text-primary"></i>
                                    </a>

                                    @if (auth()->user()->roles == $superAdmin)
                                        <!-- activer -->
                                        <a data-toggle="modal" data-target="#del{{ $data->id }}" href="#">
                                            <i class="fas fa-trash px-1 text-danger"></i>
                                        </a>
                                        <!-- /.modal -->
                                        <div class="modal fade" id="del{{ $data->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-head">
                                                        <h4 class="modal-title ">Supprission </h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('produit.destroy', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer ce produit ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default rounded-pill"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit"
                                                                class="btn btn-danger rounded-pill">Confirmer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endif
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
