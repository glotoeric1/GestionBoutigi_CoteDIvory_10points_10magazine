@extends('layout.main')
@section('main')
    @php
        $cc = 'F cfa';
        $somme = 0;
    @endphp
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ isset($type) && $type == 'detail' ? 'Detail' : 'Validation' }} du commande n°: <span
                    class="text-primary">{{ $data->numero_commande }}</span>
            </h3>
            <a class="btn btn-danger float-right rounded-pill" href="{{ route('commande.index') }}"> <i
                    class="fas fa-arrow-left"></i>
                Retourner
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if (isset($type) && $type == 'detail')
                <div class="border rounded p-3">
                    <div class="rounded border p-3 shadow-sm mb-3">
                        <h5 class="text-center">Information du commande</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="rounded border p-3 shadow-sm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="border rounded p-2">
                                            <strong>Fournisseur:</strong>
                                            <span>{{ $data->ShowFournisseurName($data->id_fournisseur) }}</span>
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
                                            <strong>Frais logistique:</strong>
                                            <span>{{ number_format($data->fraisLogistique, 0, '', ' ') }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="border rounded p-2">
                                            <strong>Frais de transit:</strong>
                                            <span>{{ number_format($data->fraisTransit, 0, '', ' ') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded border p-3 shadow-sm">
                                <div class="row">
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
                                    <div class="col-md-6">
                                        <p class="border rounded p-2">
                                            <strong>Date commande:</strong>
                                            <span>{{ $data->FormatDate($data->dates) }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="border rounded p-2">
                                            <strong>Fait par:</strong>
                                            <span>{{ $data->ShowUserName($data->id_user) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-3 mt-2">
                    <div class="rounded border p-3 shadow-sm mb-3">
                        <h5 class="text-center">Détail & article du commande</h5>
                    </div>
                    <div class="rounded border p-3 shadow-sm">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="table-responsive border p-2 rounded shadow-sm">
                                    <div class="text-center text-warning border mb-2">
                                        Detail avant validation
                                    </div>
                                    <table class="example3 table table-bordered table-hover w-100">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Désignation</th>
                                                <th>Qté</th>
                                                <th>Prix Achat</th>
                                                <th>Montant</th>
                                                <th>Prix V Détail</th>
                                                <th>Prix V Gros</th>
                                                <th class="{{ $data->statut == 'en cours' ? '' : 'd-none' }}">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailCmds as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->getProductNom($item->id_prod) }}</td>
                                                    <td>{{ $item->qte_commander }}</td>
                                                    <td>{{ number_format($item->prix, 0, '', ' ') }}</td>
                                                    <td>{{ number_format($item->qte_commander * $item->prix, 0, '', ' ') }}
                                                    </td>
                                                    <td>{{ number_format($item->prix_detail, 0, '', ' ') }}</td>
                                                    <td>{{ number_format($item->prix_gros, 0, '', ' ') }}</td>
                                                    <td class="{{ $data->statut == 'en cours' ? '' : 'd-none' }}">
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
                                                                                            value="{{ $item->getProductNom($item->id_prod) }}"
                                                                                            class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label for=""
                                                                                            class="form-label">Qte</label>
                                                                                        <input type="number"
                                                                                            name="qte_commander"
                                                                                            value="{{ $item->qte_commander }}"
                                                                                            class="form-control @error('qte_commander') is-invalid @enderror">
                                                                                        @error('qte_commander')
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
                                                                                            class="form-control @error('qte_commander') is-invalid @enderror">
                                                                                        @error('prix')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for=""
                                                                                            class="form-label">Prix de
                                                                                            vente detail</label>
                                                                                        <input type="number"
                                                                                            name="prix_detail"
                                                                                            value="{{ $item->prix_detail }}"
                                                                                            class="form-control @error('prix_detail') is-invalid @enderror">
                                                                                        @error('prix_detail')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for=""
                                                                                            class="form-label">Prix de
                                                                                            vente gros</label>
                                                                                        <input type="number"
                                                                                            name="prix_gros"
                                                                                            value="{{ $item->prix_gros }}"
                                                                                            class="form-control @error('prix_gros') is-invalid @enderror">
                                                                                        @error('prix_gros')
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
                                                                            <p>Voulez-vouz retirer cette ligne de commande ?
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

                            <div class="col-md-12">
                                <div class="table-responsive border p-2 rounded shadow-sm">
                                    <div class="text-center text-success border mb-2">
                                        Detail après validation
                                    </div>
                                    <table id="example3" class="table table-bordered table-hover w-100">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Désignation</th>
                                                <th>Qté valider</th>
                                                <th>Prix Achat</th>
                                                <th>Montant</th>
                                                <th>Prix V Détail</th>
                                                <th>Prix V Gros</th>
                                                <th>Date expiration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->statut != 'non valider' && 'en cours')
                                                @foreach ($detailCmds as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->getProductNom($item->id_prod) }}</td>
                                                        <td>{{ $item->qte_valider ?? 'En cours' }}</td>
                                                        <td>{{ number_format($item->prix, 0, '', ' ') }}</td>
                                                        <td>
                                                            {{ number_format($item->qte_valider * $item->prix, 0, '', ' ') }}
                                                        </td>
                                                        <td>{{ number_format($item->prix_detail, 0, '', ' ') }}</td>
                                                        <td>{{ number_format($item->prix_gros, 0, '', ' ') }}</td>
                                                        <td>
                                                            {{ $item->date_expiration ? date('d-m-Y', strtotime($item->date_expiration)) : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="border rounded p-3">
                    @if ($data->statut == 'valider')
                        <div class="row">
                            <div class="text-center text-success col-md-12">
                                <h4>Vous avez validé cette commande, le
                                    {{ $data->formatDate($data->updated_at) }}
                                </h4>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('commande.valider', $data->id) }}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-sm w-100">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 560px">Libelle</th>
                                                <th>Qte commander</th>
                                                <th>Quantite reçu<span class="text-danger">(*)</span></th>
                                                <th>Date expiration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailCmds as $key => $val)
                                                <tr>
                                                    <td>
                                                        <input class="form-control" type="hidden"
                                                            value="{{ $data->id }}"
                                                            name="id[{{ $key }}][{{ $data->id }}]">
                                                        <input type="text"
                                                            value="{{ $val->getProductNom($val->id_prod) }}"
                                                            class="form-control" readonly>
                                                    </td>

                                                    <td>
                                                        <input class="form-control" type="number"
                                                            value="{{ $val->qte_commander }}"
                                                            name="qte[{{ $key }}][{{ $data->id }}]"
                                                            readonly>
                                                    </td>
                                                    <td>
                                                        <input
                                                            class="form-control @error('qte_valider.' . $key . '.' . $data->id) is-invalid @enderror"
                                                            type="number"
                                                            value="{{ old('qte_valider.' . $key . '.' . $data->id) }}"
                                                            name="qte_valider[{{ $key }}][{{ $data->id }}]">
                                                        @error('qte_valider.' . $key . '.' . $data->id)
                                                            <span class="text-danger">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="date"
                                                            name="date_expiration[{{ $key }}][{{ $data->id }}]"
                                                            value="{{ old('date_expiration.' . $key . '.' . $data->id) }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Validation</label>
                                        <span class="text-danger">(*)</span>
                                        <select name="statut" class="form-control @error('statut') is-invalid @enderror">
                                            <option value="">---</option>
                                            <option value="valider" {{ old('statut') == 'valider' ? 'selected' : '' }}>
                                                Valider
                                            </option>
                                            <option value="non valider"
                                                {{ old('statut') == 'non valider' ? 'selected' : '' }}>
                                                Non Valider
                                            </option>
                                        </select>
                                        @error('statut')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-default rounded-pill d-none"
                                    data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-outline-success rounded-pill px-3">
                                    <i class="fas fa-check-circle"></i>
                                    Confirmer la validation
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

                <div class="border rounded p-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-1 fw-bold text-primary">
                                Paiement de la commande
                            </h5>
                            <small class="text-muted">
                                Numéro de commande :
                                <span class="fw-bold text-dark">
                                    {{ $data->numero_commande }}
                                </span>
                            </small>
                        </div>

                        <span
                            class="badge {{ $data->total_ht > $paiementCmds->sum('montant') ? 'bg-warning' : 'bg-success' }} fs-6">
                            {{ $data->total_ht > $paiementCmds->sum('montant') ? 'En cours' : 'Payé en totalité' }}
                        </span>
                    </div>
                    <div style="font-size: 18px; text-align:right; margin-bottom: 8px;">
                        Fait le : {{ $data->created_at->format('d/m/Y') }} à
                        {{ $data->created_at->format('H:i:s') }}
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="border rounded p-3 shadow-sm">
                                <div class="rounded border p-3 shadow-sm mb-3">
                                    <h5 class="text-center">Détail de paiement du commande</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm w-100 table-hover">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Date paiement</th>
                                                <th>Montant</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($paiementCmds as $paie)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($paie->date_paiement)) }}</td>
                                                    <td>{{ number_format($paie->montant, 0, '', ' ') }}</td>
                                                    <td>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#supp{{ $paie->id }}"
                                                            class="text-danger">Annuler</a>

                                                        <!-- Modal -->
                                                        <div id="supp{{ $paie->id }}" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modal-head">
                                                                        <h4 class="modal-title">Annulation</h4>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="text-center text-wrap">
                                                                            <p>Voulez-vouz annuler ce paiement ?
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <form
                                                                        action="{{ route('paiement.delete', $paie->id) }}"
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
                                                @php
                                                    $somme += $paie->montant;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Total commande :</th>
                                                <td class="bg-primary">
                                                    {{ number_format($data->total_ht, 0, '', ' ') }}{{ $cc }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Total payé :</th>
                                                <td class="bg-success">
                                                    {{ number_format($somme, 0, '', ' ') }}{{ $cc }}</td>
                                                <td></td>
                                            </tr>
                                            <tr class="{{ $data->total_ht - $somme == 0 ? 'd-none' :  '' }}">
                                                <th colspan="2">Total restant :</th>
                                                <td class="bg-warning">
                                                    {{ number_format($data->total_ht - $somme, 0, '', ' ') }}{{ $cc }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            {{-- <table class="table table-bordered border-primary">
                                <thead>
                                    <tr>
                                        <th>Total</th>
                                        <th>Montant déja payer</th>
                                        <th>Restant (a payer)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($dt->total_ht) }} {{ $cc }}</td>
                                        <td>{{ number_format($dt->montantDonner) }} {{ $cc }}</td>
                                        <td class="{{ $dt->total_ht > $dt->montantDonner ? 'bg-danger' : 'bg-success' }}">
                                            {{ number_format($dt->total_ht - $dt->montantDonner) }} {{ $cc }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
                        </div>
                        <div class="col-md-5">
                            <div class="border rounded p-3 shadow">
                                @php
                                    $result = $data->total_ht - $paiementCmds->sum('montant');
                                @endphp
                                @if ($result > 0)
                                    <div class="rounded border p-3 shadow-sm mb-3">
                                        <h5 class="text-center">Fait un nouveau paiement du commande</h5>
                                    </div>
                                    <form action="{{ route('commande.update', $data->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <input type="hidden" name="numero_commande"
                                                value="{{ $data->numero_commande }}">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="" class="form-label">Montant restant à
                                                        payer</label>
                                                    <input type="number" name="montant_restant"
                                                        value="{{ $data->total_ht - $paiementCmds->sum('montant') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="" class="form-label">Montant à payer</label>
                                                    <input type="number" name="montant" value="{{ old('montant') }}"
                                                        class="form-control @error('montant') is-invalid @enderror">
                                                    @error('montant')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="" class="form-label">Date paiement</label>
                                                    <input type="date" name="date_paiement"
                                                        value="{{ old('date_paiement') }}"
                                                        class="form-control @error('date_paiement') is-invalid @enderror">
                                                    @error('date_paiement')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="" class="form-label">Commentaire</label>
                                                    <textarea name="commentaire" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="btn" value="UPDATES"
                                                class="btn btn-primary px-3 rounded-pill">
                                                Valider le paiement
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="p-3">
                                        <h5 class="text-center">
                                            Le fournisseur
                                            "{{ $data->ShowFournisseurName($data->id_fournisseur) }}" à 
                                            reçu la totalité de son argent.
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="border rounded p-3 shadow mt-2 {{ $result > 0 ? 'd-none' : '' }}">
                                <h5 class="text-center">Vous pouvez également voir les différentes commande du fournisseur</h5>
                                <div class="text-center">
                                    <i class="fas fa-arrow-down"></i> <br>
                                    <a href="{{ route('fournisseur.show', $data->id_fournisseur) }}">Ici</a>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- /.card -->
@endsection
