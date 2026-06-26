@extends('layout.main')
@section('main')

    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Ajout d'une commande</h3>
                    <div class="card-tools">
                        <a href="{{ route('commande.index') }}" class="btn btn-danger px-3 rounded-pill">Retourner</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="row">
                    <div class="col-md-6">

                        <form action="{{ route('add.cart') }}" method="post" style="border: 3px solid green">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-11">

                                        <div class="form-group">
                                            <label for="code_barre">Produit</label>
                                            <select name="product"
                                                class="form-control select2 code_barre @error('product') is-invalid @enderror"
                                                style="width: 100%;">
                                                <option value="">...</option>
                                                @if (count($pros) > 0)
                                                    @foreach ($pros as $pro)
                                                        <option value="{{ $pro->id }}"
                                                            {{ $pro->id == old('product') ? 'selected' : '' }}>
                                                            {{ $pro->libelle }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @error('product')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="prix"></label> <br>
                                            <!-- activer -->
                                            <a class="btn btn-outline-primary code_barre mt-2" title="Ajouter au stock"
                                                data-toggle="modal" data-target="#addQte" href="#">
                                                <i class="fas fa-plus px-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>


                                <div class="row g-3">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="id_cat">Catégorie</label>
                                            <select name="id_cat" id="id_cat"
                                                class="form-control code_barre select2 @error('id_cat') is-invalid @enderror">
                                                <option value="">...</option>
                                                @if (count($cats))
                                                    @foreach ($cats as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @error('id_cat')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="prix"></label> <br>
                                            <!-- activer -->
                                            <a class="btn btn-outline-primary code_barre mt-2" title="Ajouter au stock"
                                                data-toggle="modal" data-target="#addCat" href="#">
                                                <i class="fas fa-plus px-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                {{-- @if (auth()->user()->id == '1')
                                    <div class="row g-3">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="id_type">Sous Catégorie</label>
                                                <select name="id_type" id="id_type"
                                                    class="form-control code_barre select2 @error('id_type') is-invalid @enderror">
                                                    <option value="">...</option>

                                                </select>

                                                @error('id_type')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="prix"></label> <br>
                                                <!-- activer -->
                                                <a class="btn btn-outline-primary code_barre mt-2" title="Ajouter au stock"
                                                    data-toggle="modal" data-target="#addSub" href="#">
                                                    <i class="fas fa-plus px-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="id_type" value="1">
                                @endif --}}

                                <input type="hidden" name="types" value="COMMANDES">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prix">Prix d'achat</label>
                                            <input type="number" name="prix"
                                                class="form-control prix @error('prix') is-invalid @enderror"
                                                id="prix">
                                            @error('prix')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Quantité</label>
                                            <input type="number" name="quantite" onblur="totalAchats()"
                                                class="form-control quantite @error('quantite') is-invalid @enderror"
                                                id="quantity" placeholder="">
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
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prix_detail">Prix en détail</label>
                                            <input type="number" name="prix_detail"
                                                value="{{ old('prix_detail') ?? '0' }}" onblur="calculateTotalDetail()"
                                                class="form-control prix @error('prix_detail') is-invalid @enderror"
                                                id="prix_detail" placeholder="">
                                            @error('prix_detail')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_detail">Total </label>
                                            <input type="number" name="total_detail"
                                                value="{{ old('total_detail') ?? '0' }}"
                                                class="form-control quantite @error('total_detail') is-invalid @enderror"
                                                id="total_detail" readonly>
                                            @error('total_detail')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prix_gros">Prix en gros</label>
                                            <input type="number" name="prix_gros" value="{{ old('prix_gros') ?? '0' }}"
                                                onblur="calculateTotalGros()"
                                                class="form-control prix @error('prix_gros') is-invalid @enderror"
                                                id="prix_gros" placeholder="">
                                            @error('prix_gros')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_gros">Total en gros</label>
                                            <input type="number" name="total_gros"
                                                value="{{ old('total_gros') ?? '0' }}"
                                                class="form-control quantite @error('total_gros') is-invalid @enderror"
                                                id="total_gros" readonly>
                                            @error('total_gros')
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
                                        <button type="reset" class="btn btn-warning mt-3 px-5 btnNew mx-1 rounded-pill">
                                            <i class="fas fa-times"></i> Annuler</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body" style="border: 3px solid green">
                            <label for="code_barre">La liste </label>
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
                                        @if (session('commande'))
                                            @foreach (session('commande') as $item)
                                                <tr>
                                                    <td>{{ $item['produit'] ?? '' }}</td>
                                                    <td>{{ $item['prix'] ?? '' }}</td>
                                                    <td>{{ $item['qte'] ?? '' }}</td>
                                                    <td class="totalTopay">{{ $item['prix'] * $item['qte'] ?? '' }}</td>
                                                    <input id="totalTopay" type="hidden" name="totalTopay"
                                                        value="{{ $item['prix'] * $item['qte'] ?? '' }}">
                                                    <td>

                                                        <!-- activer -->
                                                        <a data-toggle="modal" data-target="#edit{{ $item['id'] ?? '' }}"
                                                            href="#">
                                                            <i class="fas fa-edit px-1 text-primary"></i>
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
                                                                    <form action="{{ route('update.cart') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="types"
                                                                                value="COMMANDES">
                                                                            <input type="hidden" name="id"
                                                                                value="{{ $item['id'] ?? '' }}">
                                                                            <div class="form-group">
                                                                                <label for="quantite">Quantité</label>
                                                                                <input type="number" name="quantite"
                                                                                    class="form-control quantite @error('quantite') is-invalid @enderror">
                                                                                @error('quantite')
                                                                                    <span class="text-danger">
                                                                                        {{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button"
                                                                                class="btn btn-outline-default rounded-pill"
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
                                                        <!-- activer -->
                                                        <a data-toggle="modal" data-target="#del{{ $item['id'] ?? '' }}"
                                                            href="#">
                                                            <i class="fas fa-trash px-1 text-danger"></i>
                                                        </a>
                                                        <!-- /.modal -->
                                                        <div class="modal fade" id="del{{ $item['id'] ?? '' }}">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modal-head">
                                                                        <h4 class="modal-title ">Supprission </h4>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form
                                                                        action="{{ route('remove.cart', [$item['id'] ?? '', 'COMMANDES']) }}"
                                                                        method="get">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <p>
                                                                                Voulez vous supprimer ce produit ?
                                                                            </p>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
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
                            <form action="{{ route('commande.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="types" value="COMMANDES">
                                <div class="row">
                                    <div class="col-md-12">
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
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fraisTransit">Frais de Transit</label>
                                            <input type="number" name="fraisTransit"
                                                value="{{ old('fraisTransit') ?? '0' }}"
                                                class="form-control code_barre @error('fraisTransit') is-invalid @enderror"
                                                id="fraisTransit" placeholder="">
                                            @error('fraisTransit')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fraisLogistique">Frais de logistique </label>
                                            <div class="input-group">
                                                <input type="number" name="fraisLogistique"
                                                    value="{{ old('fraisLogistique') ?? '0' }}"
                                                    class="form-control code_barre @error('fraisLogistique') is-invalid @enderror"
                                                    id="fraisLogistique" placeholder="">

                                                <div class="input-group-append ml-2">
                                                    <a id="btnCal" onclick="totalAchat()"
                                                        class="btn btn-outline-primary code_barre" title="Appliquez">
                                                        <i class="fas fa-check px-1"></i>
                                                    </a>
                                                </div>

                                            </div>
                                            @error('fraisLogistique')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                <p id="success" class="text-success text-center"></p>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="montant">Montant payer à la commande</label>
                                            <input type="text" name="montantDonner"
                                                value="{{ old('montantDonner') ?? '0' }}" onblur="CalculateBalance()"
                                                class="form-control montant @error('montantDonner') is-invalid @enderror"
                                                id="montantDonner" placeholder="">
                                            <span id="err" class="text-danger"></span>
                                            @error('montantDonner')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="benefice">Reste à payer</label>
                                            <input type="text" name="restant" onblur="CalculateBalanceBeforePay()"
                                                class="form-control restant @error('restant') is-invalid @enderror"
                                                id="restant" placeholder="" readonly required>
                                            @error('restant')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="date" name="dates"
                                                value="@php echo date('Y-m-d'); @endphp"
                                                class="form-control code_barre @error('dates') is-invalid @enderror"
                                                id="dates" placeholder="">
                                            @error('dates')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="fournisseur">Fournisseur</label>
                                            <div class="input-group">
                                                <select name="id_fournisseur" id="fournisseur"
                                                    class="form-control code_barre select2 @error('id_fournisseur') is-invalid @enderror">
                                                    <option value="">...</option>
                                                    @if (count($fours))
                                                        @foreach ($fours as $four)
                                                            <option value="{{ $four->id }}">
                                                                {{ $four->nom_fournisseur }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="input-group-append ml-2">
                                                    <a class="btn btn-outline-primary code_barre" title="Ajouter au stock"
                                                        data-toggle="modal" data-target="#addFournisseur" href="#">
                                                        <i class="fas fa-plus px-1"></i>
                                                    </a>
                                                </div>

                                            </div>
                                            @error('id_fournisseur')
                                                <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('remove.cart', [session()->getId(), 'COMMANDES']) }}"
                                        class="btn btn-warning btn-lg mt-3 px-3 mr-2 rounded-pill">
                                        Nouveau
                                    </a>
                                    <button type="submit" name="valider" value="valider"
                                        class="btn btn-success btn-lg mt-3 px-3 mr-2 rounded-pill">
                                        Valider
                                    </button>
                                    <button type="submit" name="valider" value="print"
                                        class="btn btn-success btn-lg mt-3 px-3 rounded-pill">
                                        Valider & Impr
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
    <div class="modal fade" id="addQte">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-head">
                    <h4 class="modal-title ">Ajouter Produit</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('stcoks.add') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="numero_charge">Libelle</label>
                                <input type="text" name="libelle"
                                    class="form-control @error('numero_charge') is-invalid @enderror">
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

    <div class="modal fade" id="addCat">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-head">
                    <h4 class="modal-title ">Ajouter Catégorie</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('categorie.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="nom_categorie">Catégorie</label>
                                <input type="text" name="nom_categorie"
                                    class="form-control @error('nom_categorie') is-invalid @enderror">
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

    <div class="modal fade" id="addSub">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-head">
                    <h4 class="modal-title ">Ajouter Sous Catégorie</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('type.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="nom_type">Sous Catégorie</label>
                                <input type="text" name="nom_type"
                                    class="form-control @error('nom_type') is-invalid @enderror">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="categorie">Catégorie</label>
                                <select name="categorie" id="categorie"
                                    class="form-control code_barre @error('categorie') is-invalid @enderror">
                                    <option value="">...</option>
                                    @if (count($cats))
                                        @foreach ($cats as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                                        @endforeach
                                    @endif
                                </select>
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

    <div class="modal fade" id="addFournisseur">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-head">
                    <h4 class="modal-title ">Ajouter un Fournisseur</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('fournisseur.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="nom_fournisseur">Prenom et Nom</label>
                                <input type="text" name="nom_fournisseur"
                                    class="form-control @error('nom_fournisseur') is-invalid @enderror"
                                    id="nom_fournisseur" placeholder="">
                                @error('nom_fournisseur')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="contact_fournisseur">Contact</label>
                                <input type="text" name="contact_fournisseur"
                                    class="form-control @error('contact_fournisseur') is-invalid @enderror"
                                    id="contact_fournisseur" placeholder="">
                                @error('contact_fournisseur')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="email_fournisseur">Email</label>
                                <input type="text" name="email_fournisseur"
                                    class="form-control @error('email_fournisseur') is-invalid @enderror"
                                    id="email_fournisseur" placeholder="">
                                @error('email_fournisseur')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="adresse_fournisseur">Address</label>
                                <input type="text" name="adresse_fournisseur"
                                    class="form-control @error('adresse_fournisseur') is-invalid @enderror"
                                    id="adresse_fournisseur" placeholder="">
                                @error('adresse_fournisseur')
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
    <!-- /.modal -->
@endsection

@section('scripts')
    <script>
        //Get all students information
        // jQuery('select[name="id_cat"]').on('change', function() {
        //     var studentId = jQuery(this).val();
        //     if (studentId) {
        //         jQuery.ajax({
        //             url: '/getcat/' + studentId,
        //             type: "GET",
        //             dataType: "json",
        //             success: function(data) {
        //                 if (data) {
        //                     $('#id_type').empty();
        //                     $('#id_type').append('<option hidden>...</option>');
        //                     $.each(data, function(key, course) {
        //                         $('select[name="id_type"]').append('<option value="' + course
        //                             .id + '">' + course.nom_type + '</option>');
        //                     });
        //                 } else {
        //                     $('#id_type').empty();
        //                 }

        //             },
        //             error: function(xhr, status, error) {
        //                 console.error(xhr);
        //             }
        //         });
        //     } else {
        //         $('#id_type').empty();
        //     }
        // });


        function totalAchat() {
            var montantApayer = parseInt(document.getElementById("montantApayer").value);
            var fraisLogistique = parseInt(document.getElementById("fraisLogistique").value);
            var fraisTransit = parseInt(document.getElementById("fraisTransit").value);
            var somme = 0;
            if (typeof fraisLogistique === "number" && typeof fraisTransit === "number" && typeof montantApayer ===
                "number" && !isNaN(fraisLogistique) && !isNaN(fraisTransit) && !isNaN(montantApayer)) {
                somme = fraisLogistique + fraisTransit;
                document.getElementById('montantApayer').value = montantApayer + somme;
                document.getElementById('success').innerHTML = "Les charge(s) sont appliqueés !";
                document.getElementById('btnCal').style.display = "none";
            }
        }

        function calculateTotalDetail() {
            var quantite = parseInt(document.getElementById("quantity").value);
            var prix_detail = parseInt(document.getElementById("prix_detail").value);
            if (typeof quantite === "number" && typeof prix_detail === "number" && !isNaN(prix_detail) && !isNaN(
                    quantite)) {
                document.getElementById('total_detail').value = quantite * prix_detail;
            }
        }

        function calculateTotalGros() {
            var quantite = parseInt(document.getElementById("quantity").value);
            var prix_gros = parseInt(document.getElementById("prix_gros").value);
            if (typeof quantite === "number" && typeof prix_gros === "number" && !isNaN(prix_gros) && !isNaN(quantite)) {
                document.getElementById('total_gros').value = quantite * prix_gros;
            }
        }

        function CalculateBalance() {
            var montantApayer = parseInt(document.getElementById("montantApayer").value);
            var montantDonner = parseInt(document.getElementById("montantDonner").value);
            var somme = 0;
            if (typeof montantDonner === "number" && typeof montantApayer === "number" && !isNaN(montantDonner) && !isNaN(
                    montantApayer)) {
                somme = montantApayer - montantDonner;
                document.getElementById('restant').value = somme;
            }
        }
    </script>
@endsection
