@extends('layout.main')
@section('main')
                @php
    $currency = 'F cfa';
                @endphp
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des Ventes</h3>
                        <a class="btn btn-outline-primary float-right rounded-pill mx-2" href="{{ route('vente.create') }}"> <i
                                class="fa fa-plus"></i> Ajouter</a>
                        <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen">
                            <i class="fas fa-search"></i> Recherche Avancé </a>
                        <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()"
                            id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
                    </div>
                    <!-- -->
                    <div id="form" class="mb-3 d-none">
                        <form action="{{ route('seach.Item') }}" method="get" class="border p-3">
                            @csrf
                            <input type="hidden" name="types" id="" value="VENTES">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="montant" class="form-label">Date debut</label>
                                        <input type="date" name="dateDebut"
                                            class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
                                        @error('dateDebut')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="montant" class="form-label">Date fin</label>
                                        <input type="date" name="dateFin" class="form-control  @error('dateFin') is-invalid @enderror"
                                            id="dateFin" placeholder="">
                                        @error('dateFin')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="montant" class="form-label">Vendeur(us)</label>
                                        <select name="username" id="username"
                                            class="form-control @error('username') is-invalid @enderror">
                                            <option value="">...</option>
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
                                        <label for="montant" class="form-label">Catégorie</label>
                                        <select name="categorie" id="id_categorie" class="form-control @error('id_categorie') is-invalid @enderror">
                                            <option value="">...</option>
                                            @if (count($cats) > 0)
                                                @foreach ($cats as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        @error('montantApayer')
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
                                                <a href="{{ route('vente.show', $data->id) }}">
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
                                                            <a class="mr-3" data-toggle="modal" data-target="#print{{ $data->id }}" href="#">
                                                                <i class="fas fa-print px-1 text-info"></i> Facture
                                                            </a>
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <a data-toggle="modal" class="" data-target="#printLiv{{ $data->id }}" href="#">
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
                                                                    <button type="submit" class="btn btn-outline-success rounded-pill">
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
                                                <!-- /.modal -->

                                                <!-- activer -->
                                                <a href="{{ route('vente.show_valide_detail', $data->id) }}">
                                                    <i class="fas fa-clipboard-check px-1 text-info"></i>
                                                </a>
                                                <!-- /.modal -->

                                                {{-- <div class="modal fade" id="bal32{{ $data->id }}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-head">
                                                                <h4 class="modal-title ">
                                                                    @if ($check == 1)
                                                                    <h4>Nom : {{ $data->getClt($data->client_id)->nom }} </h4>
                                                                    @endif
                                                                </h4>
                                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>Contact : {{ $data->contact }} <br> ID : {{ $data->num_vente }}
                                                                </h4>
                                                                <hr>
                                                                <h5 class="float-end">
                                                                    @if ($data->tva == '')
                                                                    Total HT : {{ number_format($data->total_ht) }}
                                                                    {{ $currency }}<br>
                                                                    @else
                                                                    TVA : @if ($data->tva == '0.05')
                                                                    5%
                                                                    @else
                                                                    18%
                                                                    @endif <br>
                                                                    Total HT : {{ number_format($data->total_ht) }}
                                                                    {{ $currency }}<br>
                                                                    Total TVA : {{ number_format($data->total_tva) }}
                                                                    {{ $currency }}<br>
                                                                    Total TTC : {{ number_format($data->total_ttc) }}
                                                                    {{ $currency }}<br>
                                                                    @endif
                                                                    Montant Donner :
                                                                    {{ number_format($data->montantDonner) }}{{ $currency }}<br>
                                                                    Reduction : <span class="text-danger">{{ number_format($data->reduction)
                                                                        }}{{ $currency }}
                                                                    </span> <br>
                                                                    @if ($data->tva == '')
                                                                    @if ($data->total_ht <= $data->montantDonner)
                                                                        Le Restant : <span class="text-success">0
                                                                            {{ $currency }} </span>
                                                                        @else
                                                                        Le Restant : <span class="text-danger">{{ number_format($data->total_ht
                                                                            - $data->montantDonner) }}
                                                                            {{ $currency }} </span>
                                                                        @endif
                                                                        @else
                                                                        @if ($data->total_ttc <= $data->montantDonner)
                                                                            Le Restant : <span class="text-success">0
                                                                                {{ $currency }} </span>
                                                                            @else
                                                                            Le Restant : <span class="text-danger">{{
                                                                                number_format($data->total_ttc - $data->montantDonner) }}
                                                                                {{ $currency }} </span>
                                                                            @endif
                                                                            @endif

                                                                </h5>
                                                                <table class="table table-hover text-nowrap">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Produit</th>
                                                                            <th>Prix</th>
                                                                            <th>Qté</th>
                                                                            <th>Total</th>
                                                                            @if (auth()->user()->roles == 'Admin')
                                                                            <th>Action</th>
                                                                            @endif
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>{{ $data->nom_produit }}</td>
                                                                            <td>{{ number_format($data->prix) }} {{ $currency }}
                                                                            </td>
                                                                            <form action="{{ route('vente.update', [$data->id]) }}"
                                                                                method="POST" class="row gx-3 gy-2 align-items-center">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <td>
                                                                                    <input type="hidden" name="clientId"
                                                                                        value="{{ $data->client_id }}">
                                                                                    <input type="hidden" name="prix" value="{{ $data->prix }}">
                                                                                    <input type="number" size="3" name="qty"
                                                                                        value="{{ $data->quantite }}">
                                                                                    <button class="btn btn-outline-primary" type="submit"> <i
                                                                                            class="fas fa-edit"></i></button>
                                                                                </td>
                                                                                <td id="totalShow">
                                                                                    {{ number_format($data->prix * $data->quantite) }}
                                                                                </td>
                                                                            </form>

                                                                            <form
                                                                                action="{{ route('vente.deleteAfterBy', [$data->id, $data->client_id]) }}"
                                                                                method="POST" class="row gx-3 gy-2 align-items-center">
                                                                                @csrf
                                                                                @if (auth()->user()->roles == 'Admin')
                                                                                <td>
                                                                                    <button class="btn btn-outline-danger" type="submit"> <i
                                                                                            class="fas fa-trash"></i></button>
                                                                                </td>
                                                                                @endif
                                                                            </form>

                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default rounded-pill"
                                                                    data-dismiss="modal">Fermer</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div> --}}
                                                <!-- /.modal -->

                                                @if (auth()->user()->roles == 'Admin')
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
                                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('vente.deleteAfterBy', [$data->id, $data->client_id]) }}"
                                                                    method="POST" class="rows gx-3 gy-2 align-items-center">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <p>
                                                                            Voulez vous supprimer cette vente ?
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default rounded-pill"
                                                                            data-dismiss="modal">Fermer</button>
                                                                        @if (auth()->user()->roles == 'Admin')
                                                                            <button type="submit"
                                                                                class="btn btn-outline-danger rounded-pill">Confirmer</button>
                                                                        @endif
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->
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