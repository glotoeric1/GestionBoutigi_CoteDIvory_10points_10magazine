@extends('layout.main')
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Clients</h3>
            <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route('client.create') }}"> <i
                    class="fas fa-plus"></i> Ajouter</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('client.index') }}" class="btn btn-outline-success mb-2">Total :
                {{ number_format($total) }}</a>
            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Contact</th>
                        <th>Adresse</th>
                        <th>Solde</th>
                        <th width="75">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->nom }}</td>
                                <td>{{ $data->contact }}</td>
                                <td>{{ $data->adresse }}</td>
                                <td>{{ number_format($data->getBalance($data->id), 2, ',', ' ') . ' ' . config('app.cc') }}</td>
                                <td>
                                    <a href="{{ route('client.show', [$data->id]) }}" class="mr-2 text-warning">
                                        <i class="fas fa-credit-card"></i>
                                    </a>

                                    <a href="{{ route('client.edit', [$data->id]) }}" class="mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if (auth()->user()->roles == 'Admin')
                                        <!-- activer -->
                                        <a data-toggle="modal" data-target="#del{{ $data->id }}" href="#">
                                            <i class="fas fa-trash px-1 text-danger"></i>
                                        </a>

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
                                                    <form action="{{ route('client.destroy', [$data->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer ce client ?
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default rounded-pill"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill">Confirmer</button>
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