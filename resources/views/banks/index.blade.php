@extends("layout.main")
@section("main")
@php
  $cc="F cfa";
  $retrait=0;
  $depot=0;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Opération bancaires</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("banks.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
        <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
        <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()" id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
    </div>
    <!-- -->
    <div id="form" class="mb-5 d-none">
      <form action="{{ route("bank.recharche") }}" method="get" class="form-control">
        @csrf
        <input type="hidden" name="types" id="" value="BANK">
      <div class="row g-3">
        <div class="col-md-4">
            <div class="form-group">
              <label for="montant" class="form-label">Date debut</label>
              <input type="date" name="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
              @error("dateDebut")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="montant" class="form-label">Date fin</label>
            <input type="date" name="dateFin" class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
            @error("dateFin")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label for="montant" class="form-label">Compte</label>
            <select name="numero_de_compte" id="" class="form-control  @error('numero_de_compte') is-invalid @enderror">
                <option value=""></option>
                @if (count($banks)>0)
                  @foreach ($banks as $data)
                    <option value="{{$data->numero}}">{{$data->numero}}</option>
                  @endforeach
                @endif
            </select>
            @error("numero_de_compte")
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
      <a href="{{route("banks.index")}}" class="btn btn-outline-danger mb-2">Nombre operation(s) : {{count($depenseT) ?? ''}}  </a>
      <a href="{{route("banks.index")}}" class="btn btn-outline-success mb-2">Dépôt : {{number_format($depenseDeport)}} {{$cc}}</a>
      <a href="{{route("banks.index")}}" class="btn btn-outline-warning mb-2">Retrait : {{number_format($depenseRetrait)}} {{$cc}}</a>
            <a href="{{route("banks.index")}}" class="btn btn-outline-info mb-2">Remise : {{number_format($depenseRemise)}} {{$cc}}</a>
      <a href="{{route("banks.index")}}" class="btn btn-outline-primary mb-2">Solde : {{number_format($depenseM)}} {{$cc}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead> 
                <tr>
                     <th>Date</th>
                    <th>Compte</th>
                    <th>Type</th>
                    <th>Nom</th>
                    <th>Opération</th>
                    <th>Montant</th>
                    <th>Effectuer Par</th>
                    <th>Détail</th>
                    <th>Banque</th>
                    <th width="100">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->formatDate($data->dates)}}</td>
                    <td>{{ $data->numero_de_compte}}</td>
                    <td>{{  $data->ShowType($data->numero_de_compte)}}</td>
                    <td>{{  $data->ShowName($data->numero_de_compte)}} </td>
                    @if( $data->operation=="Retrait")
                    <td class="bg-warning">{{ $data->operation}}</td>
                    @elseif($data->operation=="Remise")
                    <td class="bg-info">{{ $data->operation}}</td>
                    @else
                    <td class="bg-success">{{ $data->operation}}</td>
                    @endif
                    <td>{{ number_format($data->montant)}} {{$cc}}</td>
                    <td>{{ $data->done_by}}</td>
                    <td>{{ $data->descs}}</td>
                    <td>{{ $data->ShowBank($data->numero_de_compte)}}</td>
                    <td>
                        <a href="{{ route("banks.edit", [$data->id]) }}">
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
                              <form action="{{ route("banks.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer cette opération ?
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
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header modal-head">
                          <h4 class="modal-title ">
                           Compte numéro : {{ $data->numero_de_compte}} - {{$data->ShowName($data->numero_de_compte)}}
                          </h4>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                           @php
                             $retraitCompte=App\Models\Bank::where('operation', 'Retrait')->where('numero_de_compte', $data->numero_de_compte)->sum("montant");
                             $depotCompte=App\Models\Bank::where('operation', 'Dépôt')->where('numero_de_compte', $data->numero_de_compte)->sum("montant");
                           @endphp
                           <h5>Solde : {{$depotCompte-$retraitCompte}} Fcfa</h5>
                           <table class="table">
                              <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Opération</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Par</th>
                                <th scope="col">Numéro Op</th>
                              </tr>
                            </thead>
                            <tbody>
                               @foreach(App\Models\Bank::where('numero_de_compte', $data->numero_de_compte)->latest()->limit(25)->get() as $dt)
                              <tr>
                                <th class="@" scope="row">{{ $dt->formatDate($dt->dates)}}</th>
                                <td>{{ $dt->operation}}</td>
                                <td>{{number_format($dt->montant)}} {{$cc}}</td>
                                <td>{{ $dt->done_by}}</td>
                                 <td>{{ $dt->numero}}</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
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
