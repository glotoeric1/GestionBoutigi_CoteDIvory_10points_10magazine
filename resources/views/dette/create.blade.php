@php
    $url = 'https://diallo.skillcodiing.com/dette/create';
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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <b>Ajout d'une nouvelle créance</b>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('dette.index') }}" class="btn btn-danger px-3 rounded-pill">Retourner</a>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded border-success border-3 p-2">
                                <div class="text-uppercase">
                                    <h5 class="fw-bold">
                                        <b>{{ $check == 'OUI' ? 'Choisissez les articles' : 'La liste des articles' }}</b>
                                    </h5>
                                </div>
                                @if ($check == 'OUI')
                                    <form id="barcode-form">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="code_barre">Code barre</label>
                                                        <input type="text" name="code_barre"
                                                            class="search form-control code_barre @error('code_barre') is-invalid @enderror"
                                                            id="barcode-input" placeholder="" autofocus>
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
                                                        <select name="product" id="product" onblur="totalAchats()"
                                                            class="form-control code_barre select2 @error('product') is-invalid @enderror">
                                                            <option value="">...</option>
                                                            @if (count($datas) > 0)
                                                                @foreach ($datas as $data)
                                                                    <option value="{{ $data->id }}">
                                                                        {{ $data->nom_produit }}
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
                                            <input type="hidden" name="types" value="DETTES">
                                            <input type="hidden" name="id_prod" id="id_prod">
                                            <input type="hidden" name="prod" id="prod">
                                            <div class="row g-3">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword1">Nom du Produit</label>
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
                                                        <label for="quantite">En Gros / Détail</label>
                                                        <select name="options" id="options" onblur="totalAchats()"
                                                            class="form-control quantite @error('id_type') is-invalid @enderror">
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
                                                        <input type="number" step="0.01" name="quantite"
                                                            onblur="totalAchats()"
                                                            class="form-control quantite @error('quantite') is-invalid @enderror"
                                                            id="quantite" placeholder="">
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
                                                            id="total" placeholder="" readonly>
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
                                                        class="btn btn-outline-primary mt-3 px-5 btnNew mx-1 rounded-pill">
                                                        <i class="fas fa-plus"></i> Ajouter</button>
                                                    <button type="reset"
                                                        class="btn btn-outline-warning mt-3 px-5 btnNew mx-1 rounded-pill">
                                                        <i class="fas fa-times"></i> Annuler</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <table id="example3" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="400">Libelle</th>
                                                <th width="40">Qté</th>
                                                <th width="40">Prix</th>
                                                <th width="100">Action</th>
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
                                                            <!-- activer -->

                                                            <a href="#"
                                                                class="btn btn-outline-primary btn-sm dette_panier"
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded border-success border-3 p-2">
                                <div class="text-uppercase">
                                    <h5 class="fw-bold">
                                        <b>La liste des éléments dans le panier</b>
                                    </h5>
                                </div>
                                <div class="table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Prix</th>
                                                <th>Qté</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (session('dettes'))
                                                @foreach (session('dettes') as $item)
                                                    <tr>

                                                        <td> {{ $item['produit'] ?? '' }}</td>
                                                        <td>{{ $item['prix'] ?? '' }}</td>
                                                        <td>{{ $item['qte'] ?? '' }}</td>
                                                        <td class="totalTopay">{{ $item['prix'] * $item['qte'] ?? '' }}
                                                        </td>
                                                        <input id="totalTopay" type="hidden" name="totalTopay"
                                                            value="{{ $item['prix'] * $item['qte'] ?? '' }}">
                                                        <td>

                                                            <!-- activer -->
                                                            <a data-toggle="modal"
                                                                data-target="#edit{{ $item['id'] ?? '' }}"
                                                                href="#">
                                                                <i class="fas fa-edit px-1 text-primary"></i>
                                                            </a>
                                                            <!-- /.modal -->

                                                            <div class="modal fade" id="edit{{ $item['id'] ?? '' }}">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header modal-head">
                                                                            <h4 class="modal-title ">Modification </h4>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="{{ route('update.cart') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id"
                                                                                    value="{{ $item['id'] }}">
                                                                                <input type="hidden" name="types"
                                                                                    value="DETTES">
                                                                                <div class="form-group">
                                                                                    <label for="quantite">Quantité</label>
                                                                                    <input type="number" step="0.01"
                                                                                        name="quantite"
                                                                                        onblur="totalAchats()"
                                                                                        class="form-control quantite @error('quantite') is-invalid @enderror">
                                                                                    @error('quantite')
                                                                                        <span class="text-danger">
                                                                                            {{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="modal-footer justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-default rounded-pill"
                                                                                    data-dismiss="modal">Fermer</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-danger rounded-pill">Confirmer</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>
                                                            <!-- /.modal -->

                                                            <!-- activer -->
                                                            <a data-toggle="modal"
                                                                data-target="#del{{ $item['id'] ?? '' }}" href="#">
                                                                <i class="fas fa-trash px-1 text-danger"></i>
                                                            </a>

                                                            <!-- /.modal -->

                                                            <div class="modal fade" id="del{{ $item['id'] ?? '' }}">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header modal-head">
                                                                            <h4 class="modal-title ">Supprission </h4>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form
                                                                            action="{{ route('remove.cart', [$item['id'] ?? '', 'DETTES']) }}"
                                                                            method="get">
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <p>
                                                                                    Voulez vous supprimer ce produit ?
                                                                                </p>
                                                                            </div>
                                                                            <div
                                                                                class="modal-footer justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-default rounded-pill"
                                                                                    data-dismiss="modal">Fermer</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-danger rounded-pill">Confirmer</button>
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
                                    <hr>
                                </div>
                                <form action="{{ route('dette.store') }}" method="post">
                                    @csrf
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
                                                    <option value="" selected></option>
                                                    <option value="0.05">5%</option>
                                                    <option value="0.18">18%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="tva_total">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="total_tva">Total TVA</label>
                                                    <input type="text" value="{{ old('total_tva') }}"
                                                        name="total_tva"
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
                                                        value="{{ old('total_ttc') }}" name="total_ttc" id="total_ttc"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="montantDonner">Montant donné</label>
                                                <input type="text" name="montantDonner"
                                                    onblur="CalculateBalanceBeforePay()"
                                                    class="form-control montant @error('montantDonner') is-invalid @enderror"
                                                    id="montantDonner" placeholder="">
                                                @error('montantDonner')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="restant">Restant à payer</label>
                                                <input type="text" name="restant" onblur="CalculateBalanceBeforePay()"
                                                    class="form-control restant @error('restant') is-invalid @enderror"
                                                    id="restant" placeholder="" readonly>
                                                @error('restant')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="nom">Nom du Client</label>
                                                <span class="text-danger">(*)</span>
                                                <div class="input-group">
                                                    <select name="nom" id="nom"
                                                        class="form-control select2 @error('nom') is-invalid @enderror">
                                                        <option value="">---</option>
                                                        @if (count($clients) > 0)
                                                            @foreach ($clients as $data)
                                                                <option value="{{ $data->contact }};{{ $data->nom }}">
                                                                    {{ $data->nom }} - {{ $data->contact }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            title="Ajout client" data-toggle="modal"
                                                            data-target="#addClient" href="#">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('nom')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="dateApayer">Date écheance</label>
                                                <span class="text-danger">(*)</span>
                                                <input type="date" name="dateApayer" id="dateApayer"
                                                    class="form-control code_barre @error('dateApayer') is-invalid @enderror">
                                                @error('dateApayer')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Commentaire</label>
                                                <textarea name="comments" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">

                                            <a href="{{ route('remove.cart', [session()->getId(), 'DETTES']) }}"
                                                class="btn btn-warning mt-3 btn-lg px-3 mr-2 rounded-pill">
                                                Nouveau
                                            </a>

                                            <button type="submit" name="valider" value="valider"
                                                class="btn btn-success btn-lg mt-3 px-3 mr-2 rounded-pill">
                                                Valider
                                            </button>
                                            <button type="submit" name="valider" value="print"
                                                class="btn btn-outline-success btn-lg mt-3 px-3 rounded-pill">
                                                Valider
                                                & Impr
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="add_panier">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-head">
                    <h4 class="modal-title ">
                        Ajouter au panier
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('add.cart') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="categorie" id="categorie">
                            <input type="hidden" name="types" value="DETTES">
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
                    <!-- /.card-body -->
                    <div class="d-grid gap-2 text-center mb-3">
                        <button type="submit" id="saveBtn" class="btn btn-primary btn-lg rounded-pill px-5">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                        <br>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection
