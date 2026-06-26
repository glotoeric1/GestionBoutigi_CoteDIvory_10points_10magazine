@extends("layout.main")
@section("main")
@php
  $cc=" Fcfa";
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des depenses</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("depenses.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
        <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
        <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()" id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>    
    </div>
    <!-- -->
    <div id="form" class="mb-5 d-none">
      <form action="{{ route("recharche") }}" method="get" class="form-control">
        @csrf
        <input type="hidden" name="types" id="" value="VENTES">
      <div class="row g-3">
        <div class="col-md-6">
            <div class="form-group">
              <label for="montant" class="form-label">Date debut</label>
              <input type="date" name="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
              @error("dateDebut")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="montant" class="form-label">Date fin</label>
            <input type="date" name="dateFin" class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
            @error("dateFin")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      </div>
        <div class="gap-2 d-md-flex d-md-block justify-content-center">
            <button type="submit"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
            <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
        </div>
    </form>
  
  </div>
    <!-- /.card-header -->
    <div class="card-body mt-5">
      <a href="{{route("depenses.index")}}" class="btn btn-outline-success mb-2">Dépense(s) : {{count($depenseT) ?? ''}}  </a>
      <a href="{{route("depenses.index")}}" class="btn btn-outline-success mb-2">Montant Total : {{number_format($depenseM)}}{{$cc}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sujet</th>
                    <th>Détail</th>
                    <th>Montant</th>
                    <th>Effectuer Par</th>
                    <th width="100">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->FormatDate($data->dates)}}</td>
                    <td>{{ $data->titre}}</td>
                    <td>{{ $data->descs}}</td>
                    <td>{{ number_format($data->montant)}}{{$cc}}</td>
                    <td>{{ $data->done_by}}</td>
                    <td>
                      <a href="{{ route("depenses.edit", [$data->id]) }}">
                          <i class="fa fa-edit"></i>
                      </a>

                      <!-- activer -->
                        <a data-toggle="modal" data-target="#print{{$data->id}}" href="#">
                          <i class="fas fa-print px-1"></i>
                      </a>
  
                      <!-- /.modal -->
                        <div class="modal fade" id="print{{$data->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header modal-head">
                                <h4 class="modal-title ">Impression </h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form action="{{ route("depenses.print") }}" method="get">
                                @csrf
                              <div class="modal-body">
                                <p>
                                 Voulez vous Imprimer cette depense ?
                                </p>
                                 <input type="hidden" name="numero" value="{{$data->numero }}"> 
                                 <input type="hidden" name="valider" value="print" id="">
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                              </div>
                            </form>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                      <!-- /.modal -->

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
                              <form action="{{ route("depenses.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer cette depense ?
                                </p>
                                 <input type="hidden" name="numero" value="{{$data->numero }}"> 
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
                           Sujet : {{ $data->titre}}
                          </h4>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                           <h3>Dépense numéro : {{$data->numero}}</h3> <hr>
                           <p>{{$data->descs}}</p> <hr>
                           <h5>Montant : {{number_format($data->montant)}}{{$cc}}</h5>
                           <h6>Effectué par : {{$data->done_by}}</h6>
                          
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
