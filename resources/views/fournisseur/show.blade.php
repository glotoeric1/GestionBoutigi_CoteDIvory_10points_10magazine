@extends('layout.main')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Information & les paiements commandes reçu
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('fournisseur.index') }}" class="btn btn-danger px-3 rounded-pill">Retourner</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border rounded p-3">
                        <div class="rounded border p-3 shadow-sm mb-2">
                            <h5 class="text-center">Information personnel</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="rounded border p-3 shadow-sm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="border rounded p-2">
                                                <strong>Nom:</strong>
                                                <span>{{ $data->nom_fournisseur ?? '-' }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="border rounded p-2">
                                                <strong>Adresse:</strong>
                                                <span>{{ $data->adresse_fournisseur }}</span>
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
                                                <strong>Contact:</strong>
                                                <span>{{ $data->contact_fournisseur }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="border rounded p-2">
                                                <strong>Email:</strong>
                                                <span>{{ $data->email_fournisseur }}</span>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded p-3 mt-2">
                        <div class="rounded border p-3 shadow-sm mb-2">
                            <h5 class="text-center">Commande & détail paiement</h5>
                        </div>
                        <div class="rounded border p-3 shadow-sm">
                            <div class="row">
                                <div class="col-md-7 mb-2" style="height: 420px; overflow-y: auto; overflow-x: hidden;">
                                    @php
                                        $cc = 'FCFA';
                                        $somme = 0;
                                    @endphp
                                    @foreach ($data->getSupply($data->id) as $item)
                                        <div class="border bg-primary mb-2 px-2">
                                            @php
                                                $somme = 0;
                                            @endphp
                                            N° : {{ $item->numero_commande }} | Date :
                                            {{ date('d-m-Y', strtotime($item->dates)) }} | Montant :
                                            {{ number_format($item->total_ht, 0, '', ' ') . ' FCFA' }}
                                        </div>
                                        <table class="table table-bordered table-hover table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Date paiement</th>
                                                    <th>Montant</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->getSupplyDetail($item->id) as $paie)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($paie->date_paiement)) }}
                                                        </td>
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
                                                        {{ number_format($item->total_ht, 0, '', ' ') }}{{ $cc }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Total payé :</th>
                                                    <td class="bg-success">
                                                        {{ number_format($somme, 0, '', ' ') }}{{ $cc }}</td>
                                                    <td></td>
                                                </tr>
                                                <tr class="{{ $item->total_ht - $somme == 0 ? 'd-none' : '' }}">
                                                    <th colspan="2">Total restant :</th>
                                                    <td class="bg-warning">
                                                        {{ number_format($item->total_ht - $somme, 0, '', ' ') }}{{ $cc }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endforeach
                                </div>

                                <div class="col-md-5">
                                    <div class="border rounded p-3 shadow">
                                        @foreach ($data->getSupply($data->id) as $data_cmd)
                                            @php
                                                $result = $data_cmd->total_ht - $data->getTotalDonner($data->id);
                                            @endphp
                                            @if ($result > 0)
                                                <div class="rounded border p-3 shadow-sm mb-3">
                                                    <h5 class="text-center">Fait un nouveau paiement du commande</h5>
                                                </div>
                                                <form action="{{ route('commande.update', $data_cmd->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <input type="hidden" name="numero_commande"
                                                            value="{{ $data_cmd->numero_commande }}">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">
                                                                    Montant restant à payer
                                                                </label>
                                                                <input type="number" name="montant_restant"
                                                                    value="{{ $data_cmd->total_ht - $data->getTotalDonner($data->id) }}"
                                                                    class="form-control" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Montant à
                                                                    payer</label>
                                                                <input type="number" name="montant"
                                                                    value="{{ old('montant') }}"
                                                                    class="form-control @error('montant') is-invalid @enderror">
                                                                @error('montant')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Date
                                                                    paiement</label>
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
                                                                <label for=""
                                                                    class="form-label">Commentaire</label>
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
                                                @if ($loop->first)
                                                    <div class="p-3">
                                                        <h5 class="text-center">
                                                            "{{ $data->nom_fournisseur }}" à reçu la totalité de son
                                                            argent.
                                                        </h5>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    </div>
@endsection
