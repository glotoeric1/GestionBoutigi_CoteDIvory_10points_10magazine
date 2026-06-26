@extends('layout.main')

@section('main')
    <div class="row">
        <div class="col-12">

            <div class="card card-outline card-primary shadow-sm">

                <!-- HEADER -->
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2 text-primary"></i>
                        <strong>Liste des Clients</strong>
                    </h3>
                    <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route('client.create') }}">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>

                <!-- BODY -->
                <div class="card-body">
                    <!-- TOTAL BADGE -->
                    <div class="mb-3 d-flex gap-2 flex-wrap">

                        <a href="{{ route('client.index') }}"
                            class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">

                            <i class="fas fa-users me-1"></i>
                            Clients: <b>{{ number_format($total) }}</b>
                        </a>

                        <span class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">

                            <i class="fas fa-wallet me-1"></i>

                            Total Balance:
                            <b>
                                {{ number_format($datas->sum('wallet_balance') ?? 0, 0, ',', ' ') }}
                                {{ config('app.cc') }}
                            </b>

                        </span>

                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover table-striped table-borderedless">

                            <thead class="thead-light">
                                <tr>
                                    <th>Nom complet</th>
                                    <th>Contact</th>
                                    <th>Adresse</th>
                                    <th>Solde</th>
                                    <th class="text-center" width="120">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($datas as $data)
                                    <tr>

                                        <td class="align-middle font-weight-bold">
                                            <i class="fas fa-user text-muted mr-1"></i>
                                            {{ $data->nom }}
                                        </td>

                                        <td class="align-middle">
                                            <i class="fas fa-phone text-muted mr-1"></i>
                                            {{ $data->contact }}
                                        </td>

                                        <td class="align-middle">
                                            <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                            {{ $data->adresse }}
                                        </td>

                                        <td class="align-middle text-end">
                                            <span
                                                class="badge rounded-pill bg-info px-3 py-2 fw-bold fs-6 shadow-sm border border-light">
                                                <i class="bi bi-cash-coin me-1"></i>
                                                {{ number_format($data->getBalance($data->id), 0, ',', ' ') }}
                                                {{ config('app.cc') }}
                                            </span>
                                        </td>

                                        <td class="text-center align-middle">

                                            <!-- VIEW -->
                                            <a href="{{ route('client.show', [$data->id]) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('client.edit', [$data->id]) }}"
                                                class="btn btn-sm btn-outline-primary rounded-circle" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- DELETE -->
                                            @if (auth()->user()->roles == 'Admin')

                                                <button class="btn btn-sm btn-outline-danger rounded-circle" data-toggle="modal"
                                                    data-target="#del{{ $data->id }}" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <!-- MODAL -->
                                                <div class="modal fade" id="del{{ $data->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">

                                                        <div class="modal-content">

                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                    Confirmation
                                                                </h5>

                                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>

                                                            <form action="{{ route('client.destroy', [$data->id]) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')

                                                                <div class="modal-body text-center">
                                                                    <p class="mb-0">
                                                                        Voulez-vous vraiment supprimer ce client ?
                                                                    </p>
                                                                    <small class="text-muted">
                                                                        Cette action est irréversible.
                                                                    </small>
                                                                </div>

                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-secondary rounded-pill"
                                                                        data-dismiss="modal">
                                                                        Annuler
                                                                    </button>

                                                                    <button type="submit" class="btn btn-danger rounded-pill">
                                                                        Confirmer
                                                                    </button>
                                                                </div>

                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                            @endif

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            Aucun client trouvé
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection