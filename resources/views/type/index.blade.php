@extends("layout.main")
@section("main")
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des sous catégorie</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("type.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <a href="{{route("type.index")}}" class="btn btn-outline-success mb-2">Total : {{$total}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="50">N°</th>
                    <th>Sous Catégorie</th>
                    <th>Catégorie</th>
                    <th width="80">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->nom_type }}</td>
                    <td>
                     
                      @foreach (\App\Models\Categorie::where('id', $data->categorie)->get() as $sous_cat)
                      {{ $sous_cat->nom_categorie }}
                      @endforeach
                    </td>
                    <td>
                    <a href="{{ route("type.edit", [$data->id]) }}">
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
                              <form action="{{ route("type.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer ce sous catégorie ?
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
