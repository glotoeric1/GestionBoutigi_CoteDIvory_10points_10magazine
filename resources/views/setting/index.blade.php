@extends('layout.main')
@section('main')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des configurations</h3>
            <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route('settings.create') }}"> <i
                    class="fas fa-plus"></i> Ajouter</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nom d'entreprise</th>
                        <th>Contact</th>
                        <th>Type de facture</th>
                        <th>Type de service</th>
                        <th>Nom Gerant</th>
                        <th>Contact Gerant</th>
                        <th width="100">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $data)
                            <tr>
                                <td>
                                    <img src="{{ $data->logo }}" alt="{{ $data->app_name }}"
                                        style="width: 60px; height: 60px;">
                                </td>
                                <td class="@if ($data->app_statut == 'OUI') bg-success @else bg-danger @endif">
                                    {{ $data->app_name }}
                                </td>
                                <td>{{ $data->contact }}</td>
                                <td>{{ $data->address }}</td>
                                <td>{{ $data->types }}</td>
                                <td>{{ $data->name_user }}</td>
                                <td>{{ $data->contact_user }}</td>
                                <td>

                                    @if (auth()->user()->roles == 'Admin' || auth()->user()->roles == 'Super Admin')
                                        <!-- activer -->
                                        <a data-toggle="modal" data-target="#activer{{ $data->id }}" href="#">
                                            <i class="fas fa-eye px-1 text-success"></i>
                                        </a>

                                        <!-- /.modal -->
                                        <div class="modal fade" id="activer{{ $data->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header modal-head">
                                                        <h4 class="modal-title ">
                                                            @if ($data->app_statut == 'OUI')
                                                                Desactiver
                                                            @else
                                                                Activer
                                                            @endif Application
                                                        </h4>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('settings.update', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <h4>
                                                                Voulez vous @if ($data->app_statut == 'OUI')
                                                                    Desactiver
                                                                @else
                                                                    Activer
                                                                @endif cette application ?
                                                            </h4>
                                                            <p>
                                                                <label for="Code">Code d'activation</label>
                                                                <textarea name="" class="form-control" id="" cols="1" rows="2" readonly>{{ $data->code }}</textarea>
                                                            </p>
                                                            <input type="hidden" name="option" value="ACTIVER"
                                                                id="">
                                                            <input type="hidden" name="app_statut"
                                                                value="{{ $data->app_statut }}" id="">
                                                            <p>
                                                                Option Code : {{ $data->bar_option }}
                                                            </p>
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


                                        <a href="{{ route('settings.edit', [$data->id]) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>

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
                                                    <form action="{{ route('settings.destroy', [$data->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>
                                                                Voulez vous supprimer ce employé ?
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
