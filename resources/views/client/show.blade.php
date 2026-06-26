@extends('layout.main')

@section('main')
<div class="row">

    <div class="col-md-12">
        <div class="card">

            <!-- HEADER -->
        
            <div class="card-header">
                    <h3 class="card-title">
                         <b>Compte Client - Solde & Crédit</b>
                    </h3>
                <a href="{{ route('client.index') }}" class="btn btn-outline-danger float-right rounded-pill">
                    <i class="fas fa-backward"></i> Retour
                </a>
            </div>

            <div class="card-body">

                <div class="row">

                    <!-- CLIENT INFO -->
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">

                            <h5><b>Informations Client</b></h5>

                            <table class="table table-sm table-bordered mt-3">
                                <tr>
                                    <th>Nom</th>
                                    <td>{{ $data->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $data->contact ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $data->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Adresse</th>
                                    <td>{{ $data->adresse ?? '-' }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>

                    <!-- ACCOUNT SUMMARY -->
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">

                            <h5><b>Résumé du Compte</b></h5>

                            @php
                                $availableCredit = $data->credit_limit - $data->credit_used;
                            @endphp

                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        <b>Solde Wallet</b><br>
                                        {{ number_format($data->wallet_balance, 0, ',', ' '). ' ' . config('app.cc') }} 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <b>Limite Crédit</b><br>
                                        {{ number_format($data->credit_limit, 0, ',', ' '). ' ' . config('app.cc') }} 
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-warning">
                                        <b>Crédit Utilisé</b><br>
                                        {{ number_format($data->credit_used, 0, ',', ' ') . ' ' . config('app.cc') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert {{ $availableCredit < 0 ? 'alert-danger' : 'alert-primary' }}">
                                        <b>Crédit Disponible</b><br>
                                        {{ number_format($availableCredit, 0, ',', ' ') . ' ' . config('app.cc') }}
                                    </div>
                                </div>

                            </div>
                            <!-- ACTIONS RAPIDES -->
                            <div class="mt-3">

                                <label class="font-weight-bold d-block mb-2">
                                    Actions rapides
                                </label>

                                <div class="d-flex flex-wrap">

                                    {{-- ACTIVER / BLOQUER --}}
                                    <form action="{{ route('client.toggle-status', $data->id) }}"
                                        method="POST"
                                        class="mr-2 mb-2">
                                        @csrf
                                        @method('PATCH')

                                        @if($data->status === "blocked")
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-pill">
                                                <i class="fas fa-lock"></i>
                                                Client bloqué (Activer)
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="btn btn-success btn-sm rounded-pill">
                                                <i class="fas fa-lock-open"></i>
                                                Client actif (Bloquer)
                                            </button>
                                        @endif
                                    </form>

                                    {{-- JOURNAL DES OPÉRATIONS --}}
                                    <a href="{{ route('wallet.index', $data->id) }}"
                                    class="btn btn-outline-primary btn-sm rounded-pill mr-2 mb-2">
                                        <i class="fas fa-history"></i>
                                        Journal des opérations
                                    </a>

                                </div>

                            </div>

                            </div>

                        </div>
                    </div>

                    <!-- DEPOSIT FORM -->
                    <div class="col-md-12 mb-3">
                        <div class="card card-primary card-outline shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-wallet text-primary mr-2"></i>
                                    <strong>Nouveau Dépôt (Portefeuille Client)</strong>
                                </h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('client.store') }}" method="post">
                                    @csrf

                                    
                                    <input type="hidden" name="client_id" value="{{ $data->id }}">
                                    <input type="hidden" name="form_type" value="depot">

                                    <div class="row">

                                        {{-- TYPE OF TRANSACTION --}}
                                        <div class="col-md-2 mb-3">
                                            <label class="font-weight-bold">Type de transaction</label>
                                            <select name="type_mouvement" class="form-control" required>
                                                <option value="">-- Sélectionner --</option>
                                                <option value="depot">Dépôt</option>
                                                <option value="paiement_credit">Paiement Crédit</option>
                                            </select>
                                        </div>
                                        <!-- Montant -->
                                        <div class="col-md-4 mb-3">
                                            <label class="font-weight-bold">
                                                Montant du dépôt <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </span>
                                                </div>

                                                <input type="number"
                                                    name="montant"
                                                    class="form-control"
                                                    placeholder="Saisir le montant"
                                                    min="1"
                                                    required>
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-md-2 mb-3">
                                            <label class="font-weight-bold">
                                                Date du dépôt
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>

                                                <input type="text"
                                                    class="form-control bg-light"
                                                    value="{{ date('d-m-Y H:i') }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <!-- SMS -->
                                        <div class="col-md-4 mb-3">
                                            <label class="font-weight-bold d-block">
                                                Notification
                                            </label>

                                            <div class="custom-control custom-switch mt-2">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="verifier"
                                                    name="verifier"
                                                    {{ old('verifier') ? 'checked' : '' }}>

                                                <label class="custom-control-label" for="verifier">
                                                    Envoyer un SMS à
                                                    <strong>{{ $data->nom }}</strong>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Valider le dépôt
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- HISTORY -->
                    <div class="col-md-12">
                        <div class="border rounded p-3">

                            <h5><b>Historique des Transactions</b></h5>

                            <div class="table-responsive">

                                <table id="example1" class="table table-borderedless table-hover">

                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Reçu</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Total</th>
                                            <th>Payé</th>
                                            <th>Crédit</th>
                                            <th>Reste</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @foreach ($historiques as $item)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $item->num_mouvement }}</td>

                                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>

                                            <td>
                                                @switch($item->type_mouvement)

                                                    @case('depot')
                                                        <span class="badge badge-success">Dépôt</span>
                                                    @break

                                                    @case('achat_cash')
                                                        <span class="badge badge-info">Cash</span>
                                                    @break

                                                    @case('achat_credit')
                                                        <span class="badge badge-warning">Crédit</span>
                                                    @break

                                                    @case('paiement')
                                                        <span class="badge badge-primary">Paiement</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">
                                                            {{ $item->type_mouvement }}
                                                        </span>

                                                @endswitch
                                            </td>

                                            <td>{{ number_format($item->total, 0, ',', ' ') . ' ' . config('app.cc')}}</td>
                                            <td>{{ number_format($item->montant_payer, 0, ',', ' ') . ' ' . config('app.cc') }}</td>
                                            <td>{{ number_format($item->montant_credit, 0, ',', ' ') . ' ' . config('app.cc') }}</td>
                                            <td>{{ number_format($item->montant_restant, 0, ',', ' ') . ' ' . config('app.cc') }}</td>

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

</div>
@endsection