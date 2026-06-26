@extends('layout.main')
@section('main')
    @php
        $currency = 'Fcfa';
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Statistique des Ventes</h3>
            <div class="card-tools">
                <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()"
                    id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
                <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()"
                    id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
            </div>

        </div>
        <!-- -->
        <div id="form" class="mb-3 d-none">
            <form action="{{ route('seach.statistics') }}" method="get" class="border p-3">
                @csrf
                <input type="hidden" name="types" id="" value="VENTES">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="montant" class="form-label">Debut période</label>
                            <input type="date" name="dateDebut"
                                class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
                            @error('dateDebut')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="montant" class="form-label">Fin période</label>
                            <input type="date" name="dateFin" class="form-control @error('dateFin') is-invalid @enderror"
                                id="dateFin" placeholder="">
                            @error('dateFin')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="montant" class="form-label">Vendeur(s)</label>
                            <select name="username" id="username"
                                class="form-control @error('username') is-invalid @enderror">
                                <option value="">---</option>
                                @if (count($users) > 0)
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            @error('username')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">Point de vente</label>
                            <select name="id_boutique" class="form-control @error('id_boutique') is-invalid @enderror">
                                <option value="">---</option>
                                @foreach ($boutiques as $boutique)
                                    <option value="{{ $boutique->id }}">{{ $boutique->nom_boutique }}</option>
                                @endforeach
                            </select>

                            @error('id_prod')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="gap-3 d-md-flex d-md-block justify-content-center">
                    <button type="submit" name="option" value="SEARCH"
                        class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
                    <button type="submit" name="option" value="PRINT"
                        class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche & Imp</button>
                    <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
                </div>
            </form>

        </div>
        <!-- /.card-header -->
        <div class="card-body mt-2">
            <a href="{{ route('vente.index') }}" class="btn btn-outline-success mb-2">Total qté vendue :
                {{ $totalV }} </a>
            <a href="{{ route('vente.index') }}" class="btn btn-outline-success mb-2">Total Montant :
                {{ number_format($totalM) }}{{ $currency }}</a>
            @if ($totalR != '')
                <a href="{{ route('vente.index') }}" class="btn btn-outline-success mb-2">Total Réduction :
                    {{ number_format($totalR) }}{{ $currency }}</a>
                <a href="{{ route('venteCreate') }}" class="btn btn-outline-success mb-2">Total Après Réduction:
                    {{ number_format($totalM - $totalR) }}{{ $currency }}</a>
            @endif
            <table id="example1" class="table table-bordered table-sm table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° vente</th>
                        @if (auth()->user()->roles == 'Super Admin')
                            <th>Boutique</th>
                        @endif
                        <th>Client</th>
                        <th>Nbre élément</th>
                        <th>Montant total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->FormatDate($data->created_at) }}</td>
                                <td>{{ $data->num_vente }}</td>
                                @if (auth()->user()->roles == 'Super Admin')
                                    <td>
                                        {{ $data->getBoutiqueName($data->id_boutique) }}
                                    </td>
                                @endif
                                <td>{{ $data->client_id ? $data->getClt($data->client_id)->nom : '—' }}</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#detailVente{{ $data->id }}">
                                        <div class="bg-info px-3">
                                            {{ $data->detail_venteCount($data->id) . ' élément(s)' }}
                                        </div>
                                    </a>
                                </td>
                                @if ($data->tva == 0)
                                    <td>{{ number_format($data->montantDonner, 0, '', ' ') }} {{ $currency }}</td>
                                @else
                                    <td title="@if ($data->tva == '0.05') TVA : 5% @else TVA : 18% @endif ">
                                        {{ number_format($data->montantDonner, 0, '', ' ') }} {{ $currency }}
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info ml-1">
                                            @if ($data->tva == '0.05')
                                                5%
                                            @else
                                                18%
                                            @endif
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="text-primary dropdown-icon mr-1" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v px-1"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">
                                                <a class="mr-3" data-toggle="modal"
                                                    data-target="#print{{ $data->id }}" href="#">
                                                    <i class="fas fa-print px-1 text-info"></i> Facture
                                                </a>
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <a data-toggle="modal" class=""
                                                    data-target="#printLiv{{ $data->id }}" href="#">
                                                    <i class="fas fa-print px-1 text-info"></i> B. Livriason
                                                </a>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.modal Facture  -->
                                    <div class="modal fade" id="print{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">
                                                        Imprimer - Facture Nº:
                                                        {{ $data->num_vente }}
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('printInvoice') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="option" value="VENTE">
                                                    <input type="hidden" name="vente_id" value="{{ $data->id }}">

                                                    <div class="modal-body">
                                                        <div class="text-center text-wrap">
                                                            <p>
                                                                Voulez vous imprimer la facture de vente
                                                                n°:{{ $data->num_vente }} ?
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
                                                            data-dismiss="modal">Fermer
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-outline-success rounded-pill">
                                                            Confirmer
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <!-- /.modal -->
                                    <div class="modal fade" id="printLiv{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title">
                                                        @php
                                                            $tabNum = explode('-', $data->num_vente);
                                                            $num_liv = 'B-' . $tabNum[1] . '-' . $tabNum[2];
                                                        @endphp
                                                        Imprimer - Bon livraison n°:
                                                        {{ $num_liv }}
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('printInvoice') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="option" value="LIV">
                                                    <input type="hidden" name="vente_id" value="{{ $data->id }}">

                                                    <div class="modal-body">
                                                        <div class="text-center text-wrap">
                                                            <p>
                                                                Voulez vous imprimer la facture de bon de livraison vente
                                                                n°:{{ $num_liv }} ?
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
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

                                    <div class="modal fade" id="detailVente{{ $data->id }}">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title">
                                                        Detail du vente n°: {{ $data->num_vente }}
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
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
                                                                    <div
                                                                        class="table-responsive border p-2 rounded shadow-sm">
                                                                        <table
                                                                            class="example3 table table-bordered table-hover w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>N°</th>
                                                                                    <th>Désignation</th>
                                                                                    <th>Qté</th>
                                                                                    <th>Prix Achat</th>
                                                                                    <th>Montant</th>
                                                                                    <th>Statut</th>
                                                                                    <th>Magasin</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($data->detail_ventes($data->id) as $item)
                                                                                    <tr>
                                                                                        <td>{{ $loop->iteration }}</td>
                                                                                        <td>{{ $item->ShowProdNameVente($item->id_prod) }}
                                                                                        </td>
                                                                                        <td>{{ $item->quantite }}</td>
                                                                                        <td>{{ number_format($item->prix, 0, '', ' ') }}
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ number_format($item->quantite * $item->prix, 0, '', ' ') }}
                                                                                        </td>
                                                                                        <td>{{ $item->valider }}</td>
                                                                                        <td>{{ $item->ShowEntrepotName($item->stock_id) }}
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
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-default rounded-pill"
                                                        data-dismiss="modal">Fermer
                                                    </button>
                                                </div>
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
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection

@section('scripts')
    <script>
        function CalculateTotal(value) {
            var qty = value;
            alert(qty);
            if (qty != "") {
                var prix = parseInt(document.getElementById("prix").innerHTML);
                document.getElementById("hiddenMont").value = prix * qty;
                document.getElementById("showMont").innerHTML = prix * qty;
            }
            return false;

        }
    </script>
@endsection
