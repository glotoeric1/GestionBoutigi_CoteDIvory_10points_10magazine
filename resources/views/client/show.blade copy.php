@extends('layout.main')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <b>Solde & Historique de mouvement</b>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('client.index') }}" class="btn btn-danger px-3 rounded-pill">Retourner</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-left h5">
                                    <b>Information sur le compte</b>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="border rounded p-1">Nom: {{ $data->nom }}</p>
                                        <p class="border rounded p-1">Contact: {{ $data->contact ?? '-' }}</p>
                                        <p class="border rounded p-1">Email: {{ $data->email ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="border rounded p-3">Adresse: {{ $data->adresse ?? '-' }}</p>
                                        <p class="border rounded p-1">Solde:
                                            <span
                                                class="h4 fw-bold {{ $solde < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($solde, 2, ',', ' ') }}</span>
                                            FCFA
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-left h5">
                                    <b>Nouveau dépôt</b>
                                </div>
                                <form action="{{ route('client.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="type_mouvement" value="{{ __('depot') }}">
                                        <input type="hidden" name="form_type" value="{{ __('depot') }}">
                                        <input type="hidden" name="client_id" value="{{ $data->id }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="montant" class="form-label">Montant</label>
                                                <input type="number" name="montant" id="montant"
                                                    class="form-control @error('montant') is-invalid @enderror">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date_depot" class="form-label">Date d'aujourd'hui</label>
                                                <input type="datetime" name="" value="{{ date('d-m-Y H:i') }}"
                                                    id="date_depot" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary px-3 rounded-pill mr-2">
                                                <i class="fas fa-check"></i>
                                                Valider
                                            </button>
                                            <input type="checkbox" class="form-check-input ml-2 mt-3" name="verifier"
                                                id="verifier" {{ old('verifier') ? 'checked' : '' }}>
                                            <label for="verifier" class="ml-4 mt-2">
                                                Envoyer un sms à {{ $data->nom }}
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="border rounded p-3">
                                <div class="text-left h5">
                                    <b>Les mouvements du compte</b>
                                </div>
                                <div class="table-responsive">
                                    <table id="example3" class="table table-hover table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>N° reçu</th>
                                                <th>Date</th>
                                                <th>Type mouvement</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($historiques as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->num_mouvement }}</td>
                                                    <td>{{ $item->created_at->format('d-m-Y à H:i') }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $item->type_mouvement == "depot" ? 'badge-success' : 'badge-danger' }} px-4">
                                                            {{ $item->type_mouvement == 'depot' ? 'Dépôt' : 'Retrait' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($item->montant, 2, ',', ' ') . 'F' }}</td>
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