@extends("layout.main")
@section("main")
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des compte bancaires</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("comptes.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <a href="{{route("comptes.index")}}" class="btn btn-outline-success mb-2">Total : {{$total}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="250">Numéro de compte</th>
                    <th>Banque</th>
                    <th>Type de compte</th>
                    <th>Titulaire</th>
                    <th width="100">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->numero}}</td>
                    <td>{{ $data->bank}}</td>
                    <td>{{ $data->type}}</td>
                    <td>{{ $data->titulaire}}</td>
                    <td>
                        <a href="{{ route("comptes.edit", [$data->id]) }}">
                            <i class="fa fa-edit"></i>
                        </a>

                        @if (auth()->user()->roles=="Admin")
                         <!-- activer -->
                         <a data-toggle="modal" data-target="#del{{$data->id}}" href="#">
                            <i class="fas fa-trash px-1 text-danger"></i>
                        </a>
  
                      <!-- /.modal -->
  
                        <div class="modal fade" id="del{{$data->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header modal-head">
                                <h4 class="modal-title ">Supprission </h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form action="{{ route("comptes.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer cette catégorie ?
                                </p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
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
