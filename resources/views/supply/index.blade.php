@extends('layout.main')
@section('main')
    @php
        $cc = 'F cfa';
        $qteTotal = 0;
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des commandes</h3>
            <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route('commande.create') }}"> <i
                    class="fas fa-plus"></i> Ajouter</a>
            <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen">
                <i class="fas fa-search"></i> Recherche Avancé </a>
            <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()"
                id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
        </div>
        <!-- -->
        <div id="form" class="mb-5 d-none">
            <form action="{{ route('supply.recharche') }}" method="get" class="form-control">
                @csrf
                <input type="hidden" name="types" id="" value="CAISSE">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date debut</label>
                            <input type="date" name="dateDebut"
                                class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
                            @error('dateDebut')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant" class="form-label">Date fin</label>
                            <input type="date" name="dateFin"
                                class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
                            @error('dateFin')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="gap-2 d-md-flex d-md-block justify-content-center">
                    <button type="submit" name="option" value="SEARCH"
                        class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
                    <!--
                                                                          <button type="submit" name="option" value="PRINT"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche & Imp</button>
                                                                          -->
                    <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
                </div>
            </form>

        </div>
        <!-- /.card-header -->
        <div class="card-body mt-5">
            <a href="{{ route('commande.index') }}" class="btn btn-outline-success mb-2">
                Qté Valider :
                {{ $qte_valider ?? '' }}
            </a>
            <a href="{{ route('commande.index') }}" class="btn btn-outline-success mb-2">
                Qté Commander :
                {{ $qte_commander ?? '' }}
            </a>
            <a href="{{ route('commande.index') }}" class="btn btn-outline-success mb-2 d-none">
                Importer :
                {{ number_format($importer) ?? '' }} {{ $cc }}
            </a>
            <a href="{{ route('commande.index') }}" class="btn btn-outline-success mb-2 d-none">
                Exporter :
                {{ number_format($exporter) ?? '' }} {{ $cc }}
            </a>
            <a href="{{ route('commande.index') }}" class="btn btn-outline-success mb-2">
                Total :
                {{ number_format($total) }}
                {{ $cc }}
            </a>
            <table id="productId" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° Commande</th>
                        <th>Fournisseur</th>
                        <th>Nbre article</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th width="120">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>
                                    {{ $data->FormatDate($data->dates) }}
                                </td>
                                <td>
                                    @if ($data->total_ht > $data->getTotalDonner($data->id))
                                        <a href="{{ route('commande.show', $data->id) }}" class="fw-bold"
                                            title="Commande impayé">
                                            <div class="bg-warning px-2">
                                                {{ $data->numero_commande }}
                                                <i class="fa fa-hand-pointer"></i>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ route('commande.show', $data->id) }}" class="fw-bold"
                                            title="Commande payé">
                                            <div class="bg-success px-2">
                                                {{ $data->numero_commande }}
                                                <i class="fa fa-hand-pointer"></i>
                                            </div>
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $data->ShowFournisseurName($data->id_fournisseur) }} </td>
                                <td>
                                    <a href="{{ route('commande.detail', $data->id) }}">
                                        <div class="bg-info px-2">
                                            {{ $data->getNbreCmdDetail($data->id) . ' élément(s)' }}
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div
                                        class="px-2  @if ($data->statut == 'en cours') bg-warning @elseif($data->statut == 'non valider') bg-danger @else bg-success @endif">
                                        @if ($data->statut == 'en cours')
                                            En cours...
                                        @elseif($data->statut == 'non valider')
                                            Non valider
                                        @else
                                            Valider
                                        @endif
                                    </div>
                                </td>

                                <td>{{ number_format($data->total_ht, 0, '', ' ') }} {{ $cc }}</td>
                                <td>
                                    <a data-toggle="modal" data-target="#printCmd{{ $data->id }}" href="#">
                                        <i class="fas fa-print text-primary px-1"></i>
                                    </a>
                                    <!-- /.modal -->
                                    <div class="modal fade" id="printCmd{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title">
                                                        Confirmer l'imptression
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('printCmd.afterOrder') }}" method="get">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                <h5>Vous avez Imprimer cette commande n°:
                                                                    <span
                                                                        class="text-primary">{{ $data->numero_commande }}
                                                                    </span>
                                                                </h5>
                                                            </div>
                                                            <input type="hidden" name="id"
                                                                value="{{ $data->id }}">
                                                            <input type="hidden" name="valider" value="print">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default rounded-pill"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-success rounded-pill">Confirmer</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    @if ($data->statut == 'Valider')
                                        <!-- activer -->
                                        <a data-toggle="modal" data-target="#commandeValider{{ $data->numero_commande }}"
                                            href="#" class="d-none">
                                            <i class="fas fa-check-circle text-success px-1"></i>
                                        </a>

                                        <!-- /.modal -->
                                        <div class="modal fade" id="commandeValider{{ $data->numero_commande }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-head">
                                                        <h4 class="modal-title ">Commande numéro :
                                                            {{ $data->numero_commande }}</h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                <h4>Vous avez valider cette commande le
                                                                    {{ $data->formatDate($data->updated_at) }}</h4>
                                                            </div>
                                                        </div>
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
                                    @else
                                        <a data-toggle="modal" data-target="#addQte{{ $data->numero_commande }}"
                                            href="#" class="d-none">
                                            <i class="fas fa-plus px-1"></i>
                                        </a>
                                        <!-- /.modal -->

                                        {{-- <div class="modal fade" id="addQte{{ $data->numero_commande }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-head">
                                                        <h4 class="modal-title ">Validation du commande - Nº:
                                                            {{ $data->numero_commande }}</h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('commande.valider', $data->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row mt-2">
                                                                <div class="col-md-12">
                                                                    <table
                                                                        class="table table-sm table-bordered table-striped table-hover">
                                                                        <thead class="table-dark">
                                                                            <tr>
                                                                                <th>Libelle</th>
                                                                                <th>Qté</th>
                                                                                <th>Date expiration</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($data->getSupply($data->numero_commande) as $key => $val)
                                                                                <tr>
                                                                                    <td>{{ $val->product }} </td>
                                                                                    <input type="hidden"
                                                                                        value="{{ $val->numero_commande }}"
                                                                                        name="id{{ $key }}">
                                                                                    <td>
                                                                                        <input class="form-control "
                                                                                            size="5" type="text"
                                                                                            value="{{ $val->qte_commander }}"
                                                                                            name="qte{{ $key }}"
                                                                                            id="qte">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input class="form-control "
                                                                                            size="5" type="date"
                                                                                            value="{{ old('date_expiration') }}"
                                                                                            name="date_expiration">
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-md-6">
                                                                    <label for="statut">Numéro du chargement</label>
                                                                    <input class="form-control " type="text"
                                                                        value="{{ old('numero_charge') }}"
                                                                        name="numero_charge">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="statut">Fait un choix</label>
                                                                    <select name="statut"
                                                                        class="form-control @error('statut') is-invalid @enderror"
                                                                        id="valider">
                                                                        <option value="">---</option>
                                                                        <option value="Valider">Valider</option>
                                                                        <option value="Non Valider">Non Valider</option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-right">
                                                            <button type="button"
                                                                class="btn btn-default rounded-pill px-3 mr-2"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit"
                                                                class="btn btn-outline-success rounded-pill px-3">Confirmer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div> --}}
                                        <!-- /.modal -->
                                    @endif

                                    <!-- activer -->
                                    <a data-toggle="modal" id="idvente" data-target="#pay{{ $data->id }}"
                                        href="#" class="d-none">
                                        <i class="fas fa-credit-card px-1 text-warning"></i>
                                    </a>

                                    <!-- /.modal -->
                                    <div class="modal fade" id="pay{{ $data->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">Numéro du commande -
                                                        {{ $data->numero_commande }} </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <span style="font-size: 16px;"> Fait le :
                                                        {{ $data->created_at->format('d/m/Y') }} à
                                                        {{ $data->created_at->format('H:i') }} </span>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-bordered border-primary">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Total</th>
                                                                        <th>Montant déja payer</th>
                                                                        <th>Restant (a payer)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>{{ number_format($data->total_ht) }}
                                                                            {{ $cc }}</td>
                                                                        <td>{{ number_format($data->montantDonner) }}
                                                                            {{ $cc }}</td>
                                                                        <td
                                                                            class="{{ $data->total_ht > $data->montantDonner ? 'bg-danger' : 'bg-success' }}">
                                                                            {{ number_format($data->total_ht - $data->montantDonner) }}
                                                                            {{ $cc }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <form action="{{ route('commande.update', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        @if ($data->total_ht > $data->montantDonner)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="montant">Montant</label>
                                                                        <input type="hidden" name="btn"
                                                                            value="UPDATES">
                                                                        <input type="hidden" name="numero_commande"
                                                                            value="{{ $data->numero_commande }}">
                                                                        <input type="number" name="montantDonner"
                                                                            id="montants" value=""
                                                                            class="form-control @error('montant') is-invalid @enderror"
                                                                            placeholder="">
                                                                        @error('montant')
                                                                            <span class="text-danger">
                                                                                {{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                </div>

                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default rounded-pill"
                                                        data-dismiss="modal">Fermer</button>
                                                    @if ($data->total_ht > $data->montantDonner)
                                                        <button type="submit"
                                                            class="btn btn-success rounded-pill">Confirmer</button>
                                                    @endif
                                                </div>

                                                </form>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <!-- activer -->
                                    <a data-toggle="modal" id="idvente" data-target="#bal{{ $data->id }}"
                                        href="#" class="d-none">
                                        <i class="fas fa-eye px-1 text-info"></i>
                                    </a>
                                    <input type="hidden" value="{{ $data->id }}" name="idcl" class="idcl">
                                    <!-- /.modal -->
                                    <div class="modal fade" id="bal{{ $data->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-head">
                                                    <h4 class="modal-title ">
                                                        Numéro du charge :
                                                        {{ $data->numero_charge ?? $data->numero_achat }}
                                                    </h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Total Avant Validation : {{ $data->prix }} x
                                                        {{ $data->qte_commander }} =
                                                        {{ number_format($data->prix * $data->qte_commander) }}
                                                        {{ $cc }}
                                                    </h6>
                                                    @if ($data->qte_valider != '')
                                                        <h6>Total Après Validation : {{ $data->prix }} x
                                                            {{ $data->qte_valider }} =
                                                            {{ number_format($data->prix * $data->qte_valider) }}
                                                            {{ $cc }}
                                                        </h6>
                                                    @endif
                                                    <h6>Fournisseur : {{ $data->fournisseur }}</h6>
                                                    <hr>
                                                    <h6>Prix détail : {{ $data->prix_detail }} <br> Prix en gros :
                                                        {{ $data->prix_gros }}</h6>
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

                                    @if (auth()->user()->roles == 'Super Admin')
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
                                                    <form action="{{ route('commande.destroy', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer cette opération ?
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
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
            <div class="float-right">
                {!! $datas->links() !!}
            </div>

        </div>
        <!-- /.card-body -->
    </div>

    <!-- /.card -->
@endsection
