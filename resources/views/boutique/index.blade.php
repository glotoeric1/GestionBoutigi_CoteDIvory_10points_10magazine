@extends('layout.main')
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des points de vente</h3>
            <a class="btn btn-outline-primary float-right rounded-pill {{ count($datas) == 10 ? 'd-none' : '' }}"
                href="{{ route('boutique.create') }}">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Nom point de vente</th>
                        <th>Contact</th>
                        <th>Gerant point de vente</th>
                        <th>Contact_Gerant</th>
                        <th>Entreprise </th>
                        <th>Date</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nom_boutique }}</td>
                                <td>{{ $data->contact }}</td>
                                <td>{{ $data->gerant_boutique }}</td>
                                <td>{{ $data->contact_gerant }}</td>
                                <td>
                                    {{ $data->getEntrepriseName($data->id_setting)->app_name }} -
                                    {{ $data->getEntrepriseName($data->id_setting)->contact }}
                                </td>
                                <td>{{ $data->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">

                                        <!-- Edit Button -->
                                        <a href="{{ route('boutique.edit', [$data->id]) }}"
                                            class="btn btn-sm btn-outline-primary mr-2" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        @if (Auth::user()->id == 1)
                                            <form action="{{ route('boutique.destroy', [$data->id]) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this boutique?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

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
