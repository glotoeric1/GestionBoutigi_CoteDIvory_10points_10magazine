@php
$url = 'https://diallo.skillcodiing.com/vente/create';
@endphp
@extends('layout.main')
<style>
    #example3_filter {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #example3_filter input[type="search"] {
        width: 180px;
        /* Adjust the width as needed */
        font-size: 18px;
        /* Adjust the font size as needed */
        height: 35px;
        /* Adjust the height as needed */
        /* font-weight: bold; */
        /* Add font-weight: bold to make the font bold */
    }
</style>
@section('main')
    <!-- general form elements -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <b>Ajout d'une nouvelle vente</b>
            </h3>
            <div class="card-tools">
                <a href="{{ route('vente.index') }}" class="btn btn-danger px-3 rounded-pill">Retourner</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="border rounded border-success border-3 p-3">
                        @if ($check == 'OUI')
                            <form id="barcode-form">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="code_barre">Code barre</label>
                                            <div class="form-group input-group">
                                                <input type="text" name="code_barre"
                                                    class="search form-control code_barre @error('code_barre') is-invalid @enderror"
                                                    id="barcode-input" placeholder="" autofocus>
                                                <button type="reset" class="btn btn-outline-warning"> <i
                                                        class="fas fa-trash"></i></button>
                                                @error('code_barre')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('add.cart') }}" method="post" style="border: 3px solid green">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="code_barre">Rechercher un produit</label>
                                                <select name="product" id="product"
                                                    class="form-control code_barre select2 @error('product') is-invalid @enderror">
                                                    <option value="">...</option>
                                                    @if (count($datas) > 0)
                                                        @foreach ($datas as $data)
                                                            <option value="{{ $data->id }}">{{ $data->nom_produit }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                @error('product')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="categorie" id="categorie">
                                    <input type="hidden" name="types" value="VENTES">
                                    <input type="hidden" name="id_prod" id="id_prod">
                                    <input type="hidden" name="prod" id="prod">
                                    <div class="row g-3">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Nom du produit</label>
                                                <input type="text" name="nom_produit"
                                                    class="form-control nom_produit @error('nom_produit') is-invalid @enderror"
                                                    id="nom_produit" placeholder="" readonly>
                                                @error('nom_produit')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="id_fournisseur">Prix</label>
                                                <input type="text" name="prix" value=""
                                                    class="form-control prix @error('prix') is-invalid @enderror"
                                                    id="prix" placeholder="">
                                                @error('prix')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="change_price">En Gros / Détail</label>
                                                <select name="options" id="options" onblur="totalAchats()"
                                                    class="form-control quantite @error('options') is-invalid @enderror">
                                                    <option value="1" selected>Prix Unitaire</option>
                                                    <option value="2">Prix en Gros</option>
                                                </select>
                                                @error('options')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quantite">Quantité</label>
                                                <input type="number" step="0.01" name="quantite" onblur="totalAchats()"
                                                    class="form-control quantite @error('quantite') is-invalid @enderror"
                                                    id="quantity">
                                                @error('quantite')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="total">Total Montant à Payer </label>
                                                <input type="text" name="total"
                                                    class="form-control total @error('total') is-invalid @enderror"
                                                    id="total" readonly>
                                                @error('total')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="card-footer">
                                            <button type="submit" id="saveBtn"
                                                class="btn btn-primary mt-3 px-5 btnNew mx-1 rounded-pill"> <i
                                                    class="fas fa-plus"></i> Ajouter</button>
                                            <button type="reset"
                                                class="btn btn-warning mt-3 px-5 btnNew mx-1 rounded-pill">
                                                <i class="fas fa-times"></i> Annuler</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="text-uppercase">
                                <h5 class="fw-bold">
                                    <b>La liste des éléments</b>
                                </h5>
                            </div>
                            <table id="example3" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="400">Libelle</th>
                                        <th width="40">Qté</th>
                                        <th width="40">Prix</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($datas) > 0)
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>{{ $data->ShowProdName($data->id_prod) }}</td>
                                                <td>{{ $data->quantite }}</td>
                                                <td>{{ $data->prix_vente_unitaire }}</td>
                                                <td>
                                                    {{-- <a class="btn btn-outline-primary" data-toggle="modal"
                                                        data-target="#add_panier{{ $data->id }}" href="#">
                                                        <i class="fas fa-plus px-1"></i>
                                                    </a> --}}
                                                    <a href="#" class="btn btn-outline-primary btn-sm panier"
                                                        data-toggle="modal" data-id="{{ $data->id ?? '' }}"
                                                        title="Ajouter">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('productBoutique.search') }}" method="get">
                                    @csrf
                                    <div class="form-group">
                                        <label for="" class="form-label">Changer le magasin</label>
                                        <div class="input-group">
                                            <select name="product_id" class="form-control select2">
                                                <option value="">---</option>
                                                @foreach ($magasins as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $magasin->id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nom_entrepot }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded border-success border-3 p-3">
                        <div class="text-uppercase">
                            <h5 class="fw-bold">
                                <b>La liste des éléments dans le panier</b>
                            </h5>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table table-hover  text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Désignation</th>
                                        <th>Qté</th>
                                        <th>Prix</th>
                                        <th>Montant</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (session('cart'))
                                        @foreach (session('cart') as $item)
                                            <tr>
                                                <td> {{ $item['produit'] ?? '' }}</td>
                                                <td>{{ $item['qte'] ?? '' }}</td>
                                                <td>{{ $item['prix'] ? number_format($item['prix'], 0, '', ' ') : '' }}</td>
                                                <td class="totalTopay">
                                                    {{  number_format($item['prix'] * $item['qte'], 0, '', ' ') }}
                                                </td>
                                                <input id="totalTopay" type="hidden" name="totalTopay"
                                                    value="{{ $item['prix'] * $item['qte'] ?? '' }}">
                                                <td>

                                                    <!-- activer -->
                                                    <a data-toggle="modal" data-target="#edit{{ $item['id'] ?? '' }}"
                                                        href="#">
                                                        <i class="fas fa-edit px-1 text-primary"></i>
                                                    </a>
                                                    <a data-toggle="modal" data-target="#supprime{{ $item['id'] ?? '' }}"
                                                        href="#">
                                                        <i class="fas fa-trash px-1 text-danger"></i>
                                                    </a>
                                                    <!-- /.modal -->

                                                    <div class="modal fade" id="edit{{ $item['id'] ?? '' }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-head">
                                                                    <h4 class="modal-title ">Modification </h4>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('update.cart') }}" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $item['id'] }}">
                                                                        <input type="hidden" name="types"
                                                                            value="VENTES">
                                                                        <div class="form-group">
                                                                            <label for="quantite">Quantité</label>
                                                                            <input type="number" step="0.01"
                                                                                name="quantite"
                                                                                value="{{ $item['qte'] }}"
                                                                                class="form-control @error('quantite') is-invalid @enderror">
                                                                            @error('quantite')
                                                                                <span class="text-danger">
                                                                                    {{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-default rounded-pill"
                                                                            data-dismiss="modal">Fermer</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary rounded-pill">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>

                                                    <div class="modal fade" id="supprime{{ $item['id'] ?? '' }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-head">
                                                                    <h4 class="modal-title ">Retrait élément</h4>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('remove.cart', [$item['id'] ?? '', 'VENTES']) }}"
                                                                    method="get">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <p class="text-center text-wrap">
                                                                            Voulez vous retirer cet élément dans le panier ?
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-default rounded-pill"
                                                                            data-dismiss="modal">Fermer</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger rounded-pill">
                                                                            Retirer
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <hr class="border border-3 border-success">
                        <form action="{{ route('vente.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="types" value="VENTES" id="ventes">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="montant">Total HT</label>
                                        <input type="text" name="total_ht"
                                            class="form-control montant float-end @error('total_ht') is-invalid @enderror"
                                            id="montantApayer" placeholder="" readonly>
                                        @error('total_ht')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="TVA">TVA</label>
                                        <select name="tva" id="tva" class="form-control quantite">
                                            <option value="" selected>---</option>
                                            <option value="0.05">5%</option>
                                            <option value="0.18">18%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none" id="tva_total">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_tva">Total TVA</label>
                                        <input type="text" value="{{ old('total_tva') }}" name="total_tva"
                                            class="form-control montant float-end @error('total_tva') is-invalid @enderror"
                                            id="total_tva" placeholder="" readonly>
                                        @error('total_tva')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="total_ttc">Total TTC</label>
                                        <input class="form-control montant" type="text"
                                            value="{{ old('total_ttc') }}" name="total_ttc" id="total_ttc" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="code_barre">Réduction</label>
                                        <span class="text-secondary"><em>(Optionnel)</em></span>
                                        <div class="input-group">
                                            <input type="number" name="reduction" value="{{ old('reduction') ?? '' }}"
                                                onblur="CalculateBalanceBeforePay()"
                                                class="form-control code_barre @error('reduction') is-invalid @enderror"
                                                id="reduction">
                                            <div class="input-group-append">
                                                <button type="button" id="btnreduction" class="btn btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <span id="ok" class="text-success"></span>
                                        @error('reduction')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="montant">Montant</label>
                                        <span class="text-danger">(*)</span>
                                        <input type="text" name="montantDonner" onblur="CalculateBalanceBeforePay()"
                                            class="form-control montant @error('montantDonner') is-invalid @enderror"
                                            id="montantDonner" placeholder="">
                                        <span id="err" class="text-danger"></span>
                                        @error('montantDonner')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="benefice">La monnaie</label>
                                        <input type="text" name="restant" onblur="CalculateBalanceBeforePay()"
                                            class="form-control restant @error('restant') is-invalid @enderror"
                                            id="restant" placeholder="" readonly>
                                        @error('restant')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nom">Nom du client</label>
                                        <span class="text-secondary"><em>(Optionnel sauf client fidèle)</em></span>
                                        <div class="input-group">
                                            <select name="nom"
                                                class="form-control code_barre select2 @error('nom') is-invalid @enderror">
                                                <option value="">---</option>
                                                @if (count($clients) > 0)
                                                    @foreach ($clients as $data)
                                                        <option
                                                            value="{{ $data->id }};{{ $data->contact }};{{ $data->nom }}">
                                                            {{ $data->nom }} - {{ $data->contact }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary"
                                                    title="Ajouter un client" data-toggle="modal"
                                                    data-target="#addClient">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @error('nom')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>


                                </div>
                                <div class="col-md-12">
                                        <!-- SMS -->
                                       
                                            <label class="font-weight-bold d-block">
                                                Notification
                                            </label>

                                            <div class="custom-control custom-switch mt-2">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="verifier"
                                                    name="verifier"
                                                    {{ old('verifier') ? 'checked' : '' }}>

                                                <label class="custom-control-label" for="verifier">
                                                    Envoyer un SMS au 
                                                    <strong>Client</strong>
                                                </label>
                                            </div>
                                        
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="code_barre">Opération</label>
                                        <select name="opp" id="opp" class="form-control quantite">
                                            <option value="VENTES">VENTE</option>
                                            <option value="PROFORMA">PROFORMA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('remove.cart', [session()->getId(), 'VENTES']) }}"
                                    class="btn btn-warning mt-3 px-4 btn-lg mr-2 rounded-pill">
                                    Nouveau
                                </a>

                                <button type="submit" name="valider" value="valider"
                                    class="btn btn-success mt-3 px-4 btn-lg mr-2 rounded-pill">
                                    Valider
                                </button>

                                <button type="submit" name="valider" value="print"
                                    class="btn btn-outline-success mt-3 px-4 btn-lg rounded-pill">
                                    Valider & Impr
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="addClient">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-head">
                            <h4 class="modal-title ">Ajouter un Client</h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('client.store') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="nom">Prénom et Nom</label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror" placeholder="">
                                        @error('nom')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="contact">Contact</label>
                                        <input type="text" name="contact"
                                            class="form-control @error('contact') is-invalid @enderror" id="contact"
                                            placeholder="">
                                        @error('contact')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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

            <div class="modal fade" id="Ajoutpanier">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-head">
                            <h4 class="modal-title ">Ajouter au panier</h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="{{ route('add.cart') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="categorie" id="categorie">
                                    <input type="hidden" name="types" value="VENTES">
                                    <input type="hidden" name="id_prod" id="id_prod">
                                    {{-- <input type="hidden" name="product" id="product"> --}}
                                    <input type="hidden" name="product_id" id="product_id">
                                    <input type="hidden" name="stock_id" id="stock_id">
                                    <input type="hidden" name="id_categorie" id="id_categorie">

                                    <input type="hidden" name="prix_detail" id="prix_detail">
                                    <input type="hidden" name="prix_en_gros" id="prix_en_gros">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">
                                                Nom du produit
                                            </label>
                                            <input type="text" name="nom_produit" id="nom_produit"
                                                class="form-control nom_produit @error('nom_produit') is-invalid @enderror"
                                                readonly>
                                            @error('nom_produit')
                                                <span class="text-danger">
                                                    {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_fournisseur">Prix</label>
                                            <input type="text" name="prix" id="prix" readonly
                                                class="form-control prix @error('prix') is-invalid @enderror">
                                            @error('prix')
                                                <span class="text-danger">
                                                    {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantite">
                                                En Gros / Détail
                                            </label>
                                            <select name="options" id="options"
                                                class="form-control nom_produit  @error('id_type') is-invalid @enderror">
                                                <option value="1" selected>
                                                    Prix Unitaire
                                                </option>
                                                <option value="2">
                                                    Prix en Gros
                                                </option>
                                            </select>
                                            @error('options')
                                                <span class="text-danger">
                                                    {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantite">Quantité</label>
                                            <input type="number" step="0.01" name="quantite" id="quantite"
                                                onblur="totalInTable()"
                                                class="form-control quantite @error('quantite') is-invalid @enderror">
                                            @error('quantite')
                                                <span class="text-danger">
                                                    {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="total">Montant</label>
                                            <input type="text" name="total" id="total"
                                                class="form-control total @error('total') is-invalid @enderror" readonly>
                                            @error('total')
                                                <span class="text-danger">
                                                    {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-3">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- modal.\ -->
        </div>
    </div>

@endsection

@section('script')
    <script>
        //Paiement Indirect and Paiement d'avancement
        jQuery('select[name="opps"]').on('change', function() {
            alert("Hello")
            var options = jQuery(this).val();
            alert("Hello")

            if (options == "PROFORMA") {
                $('#ventes').val("PROFORMA");
            } else {
                $('#ventes').val("VENTES");
            }
        });
    </script>
@endsection
