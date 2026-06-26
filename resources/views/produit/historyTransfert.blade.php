@extends('layout.main')

@section('main')

    @php
        $cc = 'Fcfa';
        $superAdmin = 'Super Admin';
    @endphp



    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Historique des Transferts de Produits
            </h3>
        </div>

        <div class="card-body">

            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Achat</th>
                        <th>Entrepôt ==> Magasin</th>
                        <th>Effectue par </th>
                        @if (auth()->user()->roles == $superAdmin)
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @if (isset($datas) && count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>

                                <td>

                                    @if ($data->statut === 'Cancelled')
                                        <span class="text-danger" title="Transfert annuler">
                                            <del>{{ $data->ShowProdName($data->id_prod) ?? '-' }}</del>
                                        </span>
                                    @else
                                        {{ $data->ShowProdName($data->id_prod) ?? '-' }}
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $data->quantite ?? 0 }}
                                    </span>
                                </td>

                                <td>{{ number_format($data->prix_achat ?? 0) }} {{ $cc }}</td>

                                <td>
                                    <div class="d-flex align-items-center">

                                        <span class="badge badge-primary px-2 py-1">
                                            {{ __('Entrepôt') }}
                                        </span>

                                        <i class="fas fa-long-arrow-alt-right mx-2 text-muted"></i>

                                        <span class="badge badge-success px-2 py-1">
                                            {{ $data->showEntrepot($data->entrepot_id)->nom_entrepot ?? '-' }}
                                        </span>

                                    </div>
                                </td>

                                <td>{{ $data->ShowUserName($data->username) ?? '-' }}</td>

                                @if (auth()->user()->roles == $superAdmin)
                                    <td>
                                        <!-- activer -->
                                        <a data-toggle="modal" data-target="#annulerTransfert{{ $data->id }}"
                                            href="#">
                                            <i class="fas fa-undo text-danger"></i>
                                        </a>
                                        <!-- /.modal -->
                                        <div class="modal fade" id="annulerTransfert{{ $data->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-head">
                                                        <h4 class="modal-title ">Annuler le transfert </h4>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('cancelTransfert') }}" method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="produit">Produit</label>
                                                                        <input type="text" class="form-control"
                                                                            name="produit" id="productBoutique"
                                                                            value="{{ $data->ShowProdName($data->id_prod) }}" readonly>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="produit">Qte réel transferer</label>
                                                                        <input type="text" class="form-control"
                                                                            name="qteStock" id="qteStock"
                                                                            value="{{ $data->quantite }}" readonly>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="produit">Qte après annulation</label>
                                                                        <input type="text" class="form-control"
                                                                            name="qteStock" id="qteStock"
                                                                            value="{{ $data->qte }}" readonly>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="produit">Qte à annuler</label>
                                                                        <input type="number"
                                                                            class="form-control @error('qte') is-invalid @enderror"
                                                                            name="qte" id="qte">
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="produit">Magasin ? </label>
                                                                        <input type="text" class="form-control"
                                                                            name="id_boutigi" id="id_bou"
                                                                            value="{{ $data->showEntrepot($data->entrepot_id)->nom_entrepot ?? '-' }}"
                                                                            readonly>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="id_prod" id="id_prod"
                                                            value="{{ $data->id_prod }}">
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default rounded-pill"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit"
                                                                class="btn btn-success rounded-pill">Valider</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

        </div>
    </div>

@endsection
