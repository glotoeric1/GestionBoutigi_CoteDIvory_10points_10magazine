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
                Ajuster la quantité | Transferer la quantité
            </h3>
            <div class="card-tools">
                @if (auth()->user()->roles != $vendeur)
                    <a class="btn btn-danger rounded-pill" href="{{ route('produit.index') }}"> <i
                            class="fas fa-arrow-left"></i> Retourner</a>
                @endif
            </div>
        </div>
        <!-- -->
        <!-- /.card-header -->
        <div class="card-body">
            <div class="border rounded p-3">
                    <div class="text-center mb-3 mt-3 h4">
                        Situation actuel du stock de : "
                        <span class="text-primary">{{ $data->ShowProdName($data->id_prod) }}
                        </span> "
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="">Quantité existante</label>
                                <div class="border rounded p-1">
                                    {{ $data->quantite ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="">Prix de vente en détail</label>
                                <div class="border rounded p-1">
                                    {{ $data->prix_vente_unitaire ? number_format($data->prix_vente_unitaire) : '-' }}{{ $cc }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="">Prix de vente en gros</label>
                                <div class="border rounded p-1">
                                    {{ $data->prix_vente_en_gros ? number_format($data->prix_vente_en_gros) : '-' }}{{ $cc }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="h6 text-center text-info">
                        <em>Voulez ajuster la quantité ?</em> <br>
                        <i class="fas fa-arrow-down"></i>
                    </div>

                    @if (auth()->user()->roles != $vendeur)
                        <!-- /.modal -->
                        <form action="{{ route('produit.addQte') }}" method="post">
                            @csrf
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <input type="number" name="qte" id=""
                                    class="form-control @error('qte') is-invalid @enderror">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-outline-primary rounded-pill">
                                    <i class="fas fa-check"></i>
                                    Ajuster
                                </button>
                            </div>
                        </form>
                    @endif
            </div>

            <!-- /.modal -->
            <!-- <hr class="border-1 border-primary opacity-100 my-3"> -->
            <div class="border rounded p-3 mt-3">
                <div class="text-center mt-3 mb-3 h4">
                        <i class="fas fa-arrow-right mr-1 text-primary"></i> 
                        Transfert de produit ou transfert de la
                        quantité dans un magasin
                </div>

                <form action="{{ route('productBoutique.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="produit">Nom du produit</label>
                                <input type="text" class="form-control" name="produit" id="produit"
                                    value="{{ $data->ShowProdName($data->id_prod) }}" readonly>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qteStock">Qte en Stock</label>
                                <input type="text" class="form-control" name="" id="qteStock"
                                    value="{{ $data->quantite }}" readonly>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantite">Qte à transferer</label>
                                <input type="number" class="form-control @error('quantite') is-invalid @enderror" name="quantite" id="quantite">
                                @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_entrepot">Magasin ?</label>
                                <select name="entrepot_id" id="id_entrepot" class="form-control select2 @error('entrepot_id') is-invalid @enderror">
                                    <option value="">---</option>
                                    @foreach ($magasins as $magasin)
                                        <option value="{{ $magasin->id }}">{{ $magasin->nom_entrepot }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('entrepot_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="id_prod" id="id_prod" value="{{ $data->id_prod }}">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary rounded-pill">Confirmer</button>
                        @if (!empty(session('invoice')))
                            <a href="{{ route('transfert.imprimer') }}" class="btn btn-outline-primary rounded-pill px-3">
                                Imprimer le bon de tranferet
                            </a>
                        @endif
                    </div>
                </form>
            </div>

        </div>

    </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
