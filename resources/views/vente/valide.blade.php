@extends('layout.main')
@section('main')
    @php
        $cc = 'F cfa';
        $somme = 0;
        use Carbon\Carbon;
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Validation | Retrait des articles vendu

            </h3>
            <a class="btn btn-danger float-right rounded-pill" href="{{ route('vente.index') }}"> <i
                    class="fas fa-arrow-left"></i>
                Retourner
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="border rounded p-3">
                <div class="rounded border p-2 shadow-sm mb-2">
                    <h5 class="text-center">Vente N°:
                        <span class="text-primary">
                            {{ $data->num_vente }}
                        </span>
                    </h5>
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
                                        <strong>Monnaie:</strong>
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

                <div class="rounded border p-2 shadow-sm mb-2 mt-2">
                    <h5 class="text-center">Liste des articles </h5>
                </div>
                <div class="rounded border p-3 shadow-sm">
                    <ul class="todo-list" data-widget="todo-list">

                        @foreach ($vente_details as $item)
                            <li>
                                <!-- drag handle -->
                                <span class="handle d-inline ml-2 border px-1">
                                    {{ $loop->iteration }}
                                </span>
                                <!-- checkbox -->
                                {{-- <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" value="" name="todo1" id="todoCheck1">
                                    <label for="todoCheck1"></label>
                                </div> --}}
                                <!-- todo text -->
                                <span class="text icheck-primary">
                                    Nom: {{ $item->ShowProdNameVente($item->id_prod) }} | Qte: {{ $item->quantite }} |
                                    Prix: {{ number_format($item->prix, 0, '', ' ') }} |
                                    Magasin {{ $item->ShowEntrepotName($item->stock_id) }}
                                </span>
                                <!-- Emphasis label -->
                                <small class="badge {{ $item->valider == 'valider' ? 'badge-success' : 'd-none' }}">
                                    @php
                                        $dernierMise = Carbon::parse($item->updated_at)->diffForHumans();
                                    @endphp
                                    <i class="far fa-clock"></i>
                                    Retirer
                                    {{ ucfirst($dernierMise) }}
                                </small>
                                <!-- General tools such as edit or delete-->
                                <div class="tools">
                                    <a href="#" data-toggle="modal" data-target="#valider{{ $item->id }}" class="{{ $item->valider == 'Non valider' ? 'text-primary' : 'text-success' }}">
                                        <i class="fas fa-check"></i>
                                        {{ $item->valider == 'Non valider' ? 'Valider le retrait' : 'Déjà retirer' }}
                                    </a>

                                </div>
                                <div id="valider{{ $item->id }}" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header modal-head">
                                                <h4 class="modal-title">Validation du retrait</h4>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            @if($item->valider == 'Non valider')
                                            <form action="{{ route('vente.valide_detail', $item->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="text-wrap text-center">
                                                        Veuillez confirmé le retrait de l'article : <span
                                                            class="text-primary"></span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <button type="button" class="btn btn-default rounded-pill px-3"
                                                        data-dismiss="modal">Annuler
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-primary rounded-pill px-3">
                                                        Confirmer
                                                    </button>
                                                </div>
                                            </form>
                                            @else
                                            <div class="modal-body">
                                              <div class="text-wrap text-center">
                                                <p class="h6">L'article est déjà retirer depuis : {{ ucfirst($dernierMise) }}</p>
                                              </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{-- <div class="row d-none">
                        <div class="col-md-12 mb-2">
                            <div class="table-responsive border p-2 rounded shadow-sm">
                                <table class="example3 table table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Désignation</th>
                                            <th>Qté</th>
                                            <th>Prix Achat</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th>Magasin</th>
                                            <th class="d-none">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vente_details as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->ShowProdNameVente($item->id_prod) }}</td>
                                                <tdx>{{ $item->quantite }}</tdx>
                                                <td>{{ number_format($item->prix, 0, '', ' ') }}</td>
                                                <td>
                                                    {{ number_format($item->quantite * $item->prix, 0, '', ' ') }}
                                                </td>
                                                <td>{{ $item->valider }}</td>
                                                <td>{{ $item->ShowEntrepotName($item->stock_id) }}</td>
                                                <td class="d-none">
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#editer{{ $item->id }}"
                                                        class="text-primary mr-2">Éditer</a>
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#annuler{{ $item->id }}"
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
                                                                                    <label for=""
                                                                                        class="form-label">Qte</label>
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
                                                                                    <label for=""
                                                                                        class="form-label">Prix
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
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">
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

                                                                <form action="{{ route('detail.delete', $item->id) }}"
                                                                    method="post">
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">

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
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
