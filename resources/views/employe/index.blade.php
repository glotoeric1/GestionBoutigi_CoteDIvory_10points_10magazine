@extends("layout.main")
@section("main")
@php
  $cc="Fcfa";
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Employés</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("employes.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <a href="{{route("employes.index")}}" class="btn btn-outline-success mb-2">Total : {{count($datas)}}</a>
      <a href="{{route("employes.index")}}" class="btn btn-outline-success mb-2">Salaire Total : {{number_format($totalS) ?? '0'}}{{$cc}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="100">N°</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Adresse</th>
                    <th>Post</th>
                    <th width="100">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->id}}</td>
                    <td>{{ $data->nom}}</td>
                    <td>{{ $data->contact}}</td>
                    <td>{{ $data->adresse}}</td>
                    <td>{{ $data->post}}</td>
                    <td>
                        <a href="{{ route("employes.edit", [$data->id]) }}">
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
                              <form action="{{ route("employes.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer ce employé ?
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
                     <!-- activer -->
                     <a data-toggle="modal" id="idvente" data-target="#bal{{$data->id}}" href="#">
                      <i class="fas fa-eye px-1 text-info"></i>
                  </a>
                  <input type="hidden" value="{{$data->id}}" name="idcl" class="idcl">
                <!-- /.modal -->

                  <div class="modal fade" id="bal{{$data->id}}">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header modal-head">
                          <h4 class="modal-title ">
                           Nom : {{ $data->nom}}
                          </h4>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <h4>Contact : {{ $data->contact}} <br> ID : {{ $data->id}} </h4> <hr>
                            <h5 class="float-end">
                              Post : {{ $data->post}} <br> 
                              Date Début : {{ date('d/m/Y', strtotime($data->dateStart))}} <br> 
                              Salaire : {{ number_format($data->salaire)}}{{$cc}}<br>  <hr>
                              Information d'urgency <br>
                              Nom : {{ $data->emergency_name}} <br>
                              Contact : {{ $data->contact_joint}} <br>
                            </h5>
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
              <!-- /.modal -->


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
