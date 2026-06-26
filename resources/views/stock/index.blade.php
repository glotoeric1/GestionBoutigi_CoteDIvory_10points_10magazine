@extends('layout.main')
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des produits</h3>
            <a class="btn btn-outline-primary float-right rounded-pill {{ count($datas) == 10 }}" href="{{ route('stock.create') }}">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom du produit</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->libelle }}</td>
                                <td>{{ $data->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-left align-items-left gap-2">

                                        <!-- Edit Button -->
                                        <a href="{{ route('stock.edit', [$data->id]) }}"
                                            class="btn btn-sm btn-outline-primary mx-2" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('stock.destroy', [$data->id]) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûre de vouloir supprimer ce magasin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
