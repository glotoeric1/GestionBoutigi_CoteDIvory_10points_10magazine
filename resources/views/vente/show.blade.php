@extends('layout.main')
@section('main')
    @php
        $cc = 'F cfa';
        $somme = 0;
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detail du vente n°: <span class="text-primary">{{ $data->num_vente }}</span>
            </h3>
            <a class="btn btn-danger float-right rounded-pill" href="{{ route('vente.index') }}"> <i
                    class="fas fa-arrow-left"></i>
                Retourner
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="border rounded p-3">
                <div class="rounded border p-3 shadow-sm mb-3">
                    <h5 class="text-center">Information du vente</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="rounded border p-3 shadow-sm">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Client :</strong>
                                        <span>{{ $data->getClt($data->client_id)->nom ?? '-' }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Montant total:</strong>
                                        <span>{{ number_format($data->total_ht, 0, '', ' ') }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Montant payé:</strong>
                                        <span>{{ number_format($data->montantDonner, 0, '', ' ') }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Montant restant:</strong>
                                        <span>{{ number_format($data->restant, 0, '', ' ') }}</span>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded border p-3 shadow-sm">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="border rounded p-2">
                                        <strong>Boutique:</strong>
                                        <span>{{ $data->getBoutiqueName($data->id_boutique) }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Date vente:</strong>
                                        <span>{{ $data->created_at->format('d/m/Y') }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="border rounded p-2">
                                        <strong>Vendeur:</strong>
                                        <span>{{ $data->ShowUserNameVente($data->username) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border rounded p-3 mt-2">
                <div class="rounded border p-3 shadow-sm mb-3">
                    <h5 class="text-center">Détail & article vendu</h5>
                </div>
                <div class="rounded border p-3 shadow-sm">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="table-responsive border p-2 rounded shadow-sm">
                                <table class="example3 table table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Désignation</th>
                                            <th>Qté x Prix Achat</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Magasin</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->ShowProdNameVente($item->id_prod) }}</td>
                                                <td>{{ $item->quantite }} x {{ number_format($item->prix, 0, '', ' ') }}</td>
                                                <td>
                                                    {{ number_format($item->quantite * $item->prix, 0, '', ' ') }}
                                                </td>
                                                <td>{{ $item->valider }}</td>
                                                <td>{{ $item->ShowEntrepotName($item->stock_id) }}</td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editer{{ $item->id }}"
                                                        class="text-primary mr-2">Éditer</a>
                                                    <a href="#" data-toggle="modal" data-target="#annuler{{ $item->id }}"
                                                        class="text-danger">Retirer</a>

                                                    <!-- Modal -->
                                                    <div id="editer{{ $item->id }}" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-head">
                                                                    <h4 class="modal-title">Édition</h4>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('detail.update', $item->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-9">
                                                                                <div class="form-group">
                                                                                    <label for=""
                                                                                        class="form-label">Désignation</label>
                                                                                    <input type="text"
                                                                                        value="{{ $item->ShowProdNameVente($item->id_prod) }}"
                                                                                        class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="form-label">Qte</label>
                                                                                    <input type="number" name="quantite"
                                                                                        value="{{ $item->quantite }}"
                                                                                        class="form-control @error('quantite') is-invalid @enderror">
                                                                                    @error('quantite')
                                                                                        <span
                                                                                            class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="" class="form-label">Prix
                                                                                        d'achat</label>
                                                                                    <input type="number" name="prix"
                                                                                        value="{{ $item->prix }}"
                                                                                        class="form-control @error('prix') is-invalid @enderror">
                                                                                    @error('prix')
                                                                                        <span
                                                                                            class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer d-flex justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-default rounded-pill px-3"
                                                                            data-dismiss="modal">Annuler
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary rounded-pill px-3">Enregistrer
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="annuler{{ $item->id }}" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-head">
                                                                    <h4 class="modal-title">Retrait</h4>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-center text-wrap">
                                                                        <p>Voulez-vouz retirer cette ligne de vente ?
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <form
                                                                    action="{{ route('VenteDetail.delete', [$data->id, $item->id]) }}"
                                                                    method="post">
                                                                    <div class="modal-footer d-flex justify-content-between">

                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="btn btn-default rounded-pill px-3"
                                                                            data-dismiss="modal">
                                                                            Annuler
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger rounded-pill px-3">
                                                                            Confirmer
                                                                        </button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection