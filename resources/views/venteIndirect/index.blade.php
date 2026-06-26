@extends('layout.main')
@php
    $currency = ' Fcfa';
    $totalPay = 0;
    $montantTotal = 0;
@endphp
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des ventes indirect</h3>
            <div class="card-tools">
                <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route('venteIndirects.create') }}"> <i
                        class="fas fa-plus"></i> Ajouter</a>
                <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2 d-none" onclick="showForm()"
                    id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
                <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()"
                    id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
            </div>
        </div>

        <!-- -->
        <div id="form" class="mb-5 d-none">
            <form action="{{ route('paiement.recherche') }}" method="get" class="form-control">
                @csrf
                <input type="hidden" name="types" id="" value="VENTES">
                <div class="row g-3">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date debut</label>
                            <input type="date" name="dateDebut"
                                class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
                            @error('dateDebut')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date fin</label>
                            <input type="date" name="dateFin"
                                class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
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
                                @if (count($datas) > 0)
                                    @foreach ($datas as $data)
                                        <option value="{{ $data->nom }}">{{ $data->nom }}</option>
                                    @endforeach
                                @endif
                            </select>

                            @error('username')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="montant" class="form-label">Produit</label>
                            <select name="id_prod" id="id_categorie"
                                class="form-control @error('id_categorie') is-invalid @enderror">
                                <option value="">...</option>
                                @if (count($datas) > 0)
                                    @foreach ($datas as $produit)
                                        <option value="{{ $produit->produit }}">{{ $produit->produit }}</option>
                                    @endforeach
                                @endif
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
        <div class="card-body mt-5">
            <a href="{{ route('venteIndirects.index') }}" class="btn btn-outline-success mb-2">Qté Total :
                {{ $totalQ ?? '0' }}</a>
            <a href="{{ route('venteIndirects.index') }}" class="btn btn-outline-success mb-2">Montant Total :
                {{ number_format($totalT ?? '0') }}{{ $currency }}</a>
            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Numéro</th>
                        <th>Nom</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th width="40">Qté</th>
                        <th width="120">Total (TVA)</th>
                        <th width="95">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->FormatDate($data->created_at) }}</td>
                                <td>{{ $data->clientId }}</td>
                                <td>{{ $data->nom }}</td>
                                <td>{{ $data->produit }}</td>
                                <td>{{ number_format($data->montant) }} {{ $currency }}</td>
                                <td>{{ $data->qte }} </td>
                                @if ($data->tva == '')
                                    <td>{{ number_format($data->total_ht) }} {{ $currency }}</td>
                                @else
                                    <td title="@if ($data->tva == '0.05') TVA : 5% @else TVA : 18% @endif ">
                                        {{ number_format($data->total_ttc) }} {{ $currency }}
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info ml-1">
                                            @if ($data->tva == '0.05')
                                                5%
                                            @else
                                                18%
                                            @endif
                                    </td>
                                @endif


                                <td>
                                    <a data-toggle="modal" id="idvente" data-target="#bal32{{ $data->id }}"
                                        href="#">
                                        <i class="fas fa-eye px-1 text-info"></i>
                                    </a>

                                    <!-- /.modal -->
                                    <div class="modal fade" id="bal32{{ $data->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">
                                                        <h4>Nom : {{ $data->nom }} </h4>
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4>Contact : {{ $data->contact }} <br> ID : {{ $data->clientId }}
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
                                                    </h5>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default rounded-pill"
                                                        data-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <a href="{{ route('venteIndirects.edit', [$data->id]) }} ">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <!--
                          <a data-toggle="modal" data-target="#pays" href="#">
                              <i class="fas fa-plus px-1"></i>
                          </a>
                        -->

                                    <!-- /.modal -->
                                    <div class="modal fade" id="pays{{ $data->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Paiement d'avance - {{ $data->nom }}</h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <h5 class="text-center">Dernier Paiement le <span
                                                        class="btn btn-outline-success">
                                                        {{ $data->updated_at->format('d/m/Y') }}</span></h5>

                                                <table class="table table-bordered border-primary">
                                                    <thead>
                                                        <tr>
                                                            <th>Prix</th>
                                                            <th>Qté</th>
                                                            <th>Montant</th>
                                                            <th>Montant déja Payé</th>
                                                            <th>Restant Reste à Payer</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ number_format($data->montant) }} {{ $currency }}
                                                            </td>
                                                            <td>{{ $data->qte }}</td>
                                                            <td>{{ number_format($data->qte * $data->montant) }}
                                                                {{ $currency }}</td>
                                                            <td>{{ number_format($data->CalculateSum($data->clientId)) }}
                                                                {{ $currency }}</td>
                                                            @if ($data->total == $data->CalculateSum($data->clientId))
                                                                <td class="btn btn-outline-success">0 {{ $currency }}
                                                                </td>
                                                            @else
                                                                <td class="btn btn-outline-danger">
                                                                    {{ number_format($data->total - $data->CalculateSum($data->clientId)) }}
                                                                    {{ $currency }}</td>
                                                            @endif

                                                        </tr>
                                                    </tbody>
                                                </table>
                                                @if ($data->montantPay < $data->qte * $data->montant)
                                                    <form action="{{ route('venteIndirects.update', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="montantPay">Montant</label>
                                                                        <input type="number" name="montantPay"
                                                                            id="montantPays" value=""
                                                                            class="form-control @error('montantPay') is-invalid @enderror"
                                                                            placeholder="">
                                                                        @error('montantPay')
                                                                            <span class="text-danger">
                                                                                {{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="id"
                                                                    value="{{ $data->id }}">
                                                                <input type="hidden" name="clientId"
                                                                    value="{{ $data->clientId }}">
                                                                <input type="hidden" name="types" value="PAY">
                                                                <input type="hidden" name="option" value="AVANCE">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default rounded-pill"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit"
                                                                class="btn btn-outline-primary rounded-pill">Confirmer</button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <!-- activer -->
                                    <a data-toggle="modal" data-target="#print{{ $data->id }}" href="#">
                                        <i class="fas fa-print px-1"></i>
                                    </a>

                                    <!-- /.modal -->

                                    <div class="modal fade" id="print{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Imprission </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('indirect.printInvoice') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="option" value="VENTE_INDIRECT"
                                                        id="">
                                                    <input type="hidden" name="clientId" value="{{ $data->clientId }}"
                                                        id="">

                                                    <div class="modal-body">
                                                        <p>
                                                            Voulez vous imprimer ce enregistrement ?
                                                        </p>
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
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('venteIndirects.destroy', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer ce enregistrement ?
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
                                        <!-- /.modal -->
                                    @endif
                                    <!-- activer -->
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
