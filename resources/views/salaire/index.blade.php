@extends("layout.main")
@php
  $currency=" Fcfa";
@endphp
@section("main")
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Salaires</h3>
        <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("salaires.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <a href="{{route("salaires.index")}}" class="btn btn-outline-success mb-2">Total : {{count($datas)}}</a>
      <a href="{{route("salaires.index")}}" class="btn btn-outline-success mb-2">Total Salaire de {{date('M')}} : {{number_format($totalS ?? '0')}}{{$currency}}</a>
      <a href="{{route("salaires.index")}}" class="btn btn-outline-success mb-2">Total Reste à payer {{date('M')}} : {{number_format($totalR ?? '0')}}{{$currency}}</a>
      <a href="{{route("salaires.index")}}" class="btn btn-outline-success mb-2">Total Bonus {{date('M')}} : {{number_format($totalB ?? '0')}}{{$currency}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Année</th>
                    <th>Mois</th>
                    <th>Salaire</th>
                    <th>Montant Réçu</th>
                    <th width="120">Montant Restant</th>
                    <th>Bonus</th>
                    <th width="110">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->FormatDate($data->created_at) }}</td>
                    <td>{{$data->ShowName($data->emp_id)}}</td>
                    <td>{{ $data->years}}</td>
                    <td>{{ $data->mois}}</td>
                    <td>{{ number_format($data->salaire)}} {{$currency}}</td>
                    <td>{{ number_format($data->montantRecu)}} {{$currency}}</td>
                    @if ($data->montantRestant==0)
                      <td class="bg-success">
                        {{ number_format($data->montantRestant)}} {{$currency}}
                      </td>
                    @else
                    <td class="bg-danger">
                      {{ number_format($data->montantRestant)}} {{$currency}}
                    </td>
                    @endif
                    @if ($data->bonus==0 || $data->bonus=="")
                    <td>
                      {{ number_format($data->bonus)}}
                    </td>
                    @else
                    <td class="bg-success">
                      {{ number_format($data->bonus)}} {{$currency}}
                    </td>
                    @endif
                    <td>
                      <a href="{{ route("salaires.edit", [$data->id]) }}">
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
                              <form action="{{ route("salaires.print") }}" method="get">
                                @csrf
                              <div class="modal-body">
                                <p>
                                 Voulez vous Imprimer ce Salaire ?
                                </p>
                                 <input type="hidden" name="pay_number" value="{{$data->pay_number }}"> 
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

                      <a data-toggle="modal" data-target="#pays{{$data->id}}" href="#">
                          <i class="fas fa-eye px-1"></i>
                      </a>

                       <!-- /.modal -->
                       <div class="modal fade" id="pays{{$data->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header modal-head">
                              <h4 class="modal-title ">Reste de Salaire -  {{$data->ShowName($data->emp_id)}}</h4>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            <table class="table table-bordered border-primary">
                              <thead>
                                <tr>
                                  <th>Salaire</th>
                                  <th>Montant Réçu</th>
                                  <th>Restant </th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>{{$data->salaire}} {{$currency}}</td>
                                  <td>{{$data->montantRecu}} {{$currency}}</td>
                                  @if ($data->montantRecu == $data->salaire )
                                    <td class="btn btn-outline-success">0 {{$currency}}</td>
                                  @else
                                    <td class="btn btn-outline-danger">{{$data->montantRestant}} {{$currency}}</td>
                                  @endif
                                 
                                </tr>
                              </tbody>
                            </table>
                            @if ($data->montantRecu < $data->salaire )
                            <form action="{{ route("salaires.update", [$data->id]) }}" method="post">
                              @csrf
                              @method("PUT")
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="montant">Montant</label>
                                    <input type="number" name="montant" id="montants" value="" class="form-control @error('montant') is-invalid @enderror"  placeholder="">
                                    @error("montant")
                                        <span class="text-danger"> {{$message}}</span>
                                    @enderror
                                </div>
                                </div>
                                <input type="hidden" name="id" value="{{$data->id}}">
                                <input type="hidden" name="types" value="PAY">
                              </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                              <button type="submit" class="btn btn-outline-primary rounded-pill">Confirmer</button>
                            </div>
                          </form>
                          @endif
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
                              <form action="{{ route("salaires.destroy", [$data->id]) }}" method="post">
                                @csrf
                                @method("DELETE")
                              <div class="modal-body">
                                <p>
                                 Voulez vous supprimer ce enregistrement ?
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
