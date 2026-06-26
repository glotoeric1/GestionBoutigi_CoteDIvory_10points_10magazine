@extends('layout.main')
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Entrers / Sortirs des stocks</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('gestions.index') }}" class="btn btn-outline-success mb-2">Qté Entrer :
                {{ $qteEntre ?? '0' }}</a>
            <a href="{{ route('gestions.index') }}" class="btn btn-outline-warning mb-2">Qté Sortir :
                {{ $qteSortie ?? '0' }}</a>
            <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Boutique</th>
                        <th>Nº: Chargement</th>
                        <th>Opération</th>
                        <th>Service</th>
                        <th>Qté en Stock</th>
                        <th>Qté Vendue</th>
                        <th>Effectué Par</th>
                        <th width="60">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->formatDate($data->created_at) }}</td>
                                <td>{{ $data->produit }}</td>
                                <td>{{ $data->id_boutique }}</td>
                                <td>{{ $data->num_charge }}</td>
                                <td class="@if ($data->operation == 'Entrer') bg-success  @else bg-warning @endif">
                                    {{ $data->operation }}</td>
                                <td>{{ $data->service }}</td>
                                <td>{{ $data->qte_en_stock }}</td>
                                <td>{{ $data->qte }}</td>
                                <td>{{ $data->user_name }}</td>
                                <td>
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
                                                    <form action="{{ route('gestions.destroy', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer ce produit ?
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

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
