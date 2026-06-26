@extends("layout.main")
@php
  $currency=" Fcfa";
  $totalPay=0;
  $montantTotal=0;
@endphp
@section("main")
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Liste des services</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <br>
      <hr>
      <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("services.create") }}"> <i class="fas fa-plus"></i> Ajouter</a>
      <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2 d-none" onclick="showForm()" id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
      <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()" id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
  </div>

  <!-- -->
  <div id="form" class="mb-5 d-none">
    <form action="{{ route("paiement.recherche") }}" method="get" class="form-control">
      @csrf
      <input type="hidden" name="types" id="" value="VENTES">
    <div class="row g-3">
      <div class="col-md-2">
          <div class="form-group">
            <label for="montant" class="form-label">Date debut</label>
            <input type="date" name="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
            @error("dateDebut")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="montant" class="form-label">Date fin</label>
          <input type="date" name="dateFin" class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
          @error("dateFin")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    </div>
      <div class="gap-3 d-md-flex d-md-block justify-content-center">
          <button type="submit" name="option" value="SEARCH"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
          <button type="submit" name="option" value="PRINT"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche & Imp</button>
          <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
      </div>
  </form>

</div>
  
    <!-- /.card-header -->
    <div class="card-body mt-5">
      <a href="{{route("services.index")}}" class="btn btn-outline-success mb-2">Qté Total : {{$totalQ}}</a>
      <a href="{{route("services.index")}}" class="btn btn-outline-success mb-2">Montant Total : {{number_format($totalT ?? '0')}}{{$currency}}</a>
      <a href="{{route("services.index")}}" class="btn btn-outline-success mb-2">Montant déja payé : {{number_format($totalP ?? '0')}}{{$currency}}</a>
      <a href="{{route("services.index")}}" class="btn btn-outline-success mb-2">Montant reste à payer : {{number_format($totalR ?? '0')}}{{$currency}}</a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th width="70">Nº Client</th>
                    <th>Nom</th>
                    <th>Service</th>
                    <th>Frais</th>
                    <th width="40">Qté</th>
                    <th width="120">Total</th>
                    <th width="120">Reste à payer </th>
                    <th width="90">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->FormatDate($data->created_at) }}</td>
                    @if ($data->CalculateSum($data->clientId) < $data->total )
                       <td class="bg-danger" title="Le Restant : {{number_format($data->CalculateSum($data->clientId))}}{{$currency}}">{{$data->clientId}}</td>
                    @else
                      <td>{{$data->clientId}}</td>
                    @endif
                    <td>{{$data->nom}}</td>
                    <td>{{ $data->titre}}</td>
                    <td>{{ number_format($data->montant)}} {{$currency}}</td>
                    <td>{{ $data->qte}} </td>
                    @if ($data->tva=="")
                      <td>{{ number_format($data->total_ht)}} {{$currency}}</td>
                      <td class="btn btn-outline-danger">{{ number_format($data->total_ht - $data->CalculateSum($data->clientId))}} {{$currency}}</td>
                    @else
                      <td title="@if($data->tva=='0.05') TVA : 5% @else TVA : 18% @endif ">{{ number_format($data->total_ttc)}} {{$currency}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info ml-1">
                        @if ($data->tva=="0.05") 5% @else 18% @endif
                      </td>
                      <td class="btn btn-outline-danger">{{ number_format($data->total_ttc - $data->CalculateSum($data->clientId))}} {{$currency}}</td>
                    @endif

                    <td>
                      <!--
                        <a href="{{-- {{- route("paiementavances.edit", [$data->id]) }} --}} ">
                            <i class="fa fa-edit"></i>
                        </a>
                      -->

                      <a data-toggle="modal" data-target="#pays{{$data->id}}" href="#">
                          <i class="fas fa-plus px-1"></i>
                      </a>

                       <!-- /.modal -->
  
                       <div class="modal fade" id="pays{{$data->id}}">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header modal-head">
                              <h4 class="modal-title ">Service -  {{$data->nom}}</h4>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <h5 class="text-center">Dernier Paiement le <span class="btn btn-outline-success"> {{$data->updated_at->format("d/m/Y")}}</span></h5>

                            <table class="table table-bordered border-primary">
                              <thead>
                                <tr>
                                  <th>Prix</th>
                                  <th>Qté</th>
                                  <th>Total</th>
                                  <th>Total déja Payé</th>
                                  <th>Reste à Payer</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>{{number_format($data->montant)}} {{$currency}}</td>
                                  <td>{{$data->qte}}</td>
                                  @if($data->tva !="")
                                    <td>{{number_format($data->total_ttc)}} {{$currency}}</td>
                                  @else 
                                    <td>{{number_format($data->total_ht)}} {{$currency}}</td>
                                  @endif
                                  <td>{{number_format($data->CalculateSum($data->clientId))}} {{$currency}}</td>
                                  @if($data->tva !="")
                                    @if ($data->total_ttc == $data->CalculateSum($data->clientId) )
                                      <td class="btn btn-outline-success">0 {{$currency}}</td>
                                    @else
                                      <td class="btn btn-outline-danger">{{number_format($data->total_ttc - $data->CalculateSum($data->clientId))}} {{$currency}}</td>
                                    @endif
                                  @else 
                                      @if ($data->total_ht == $data->CalculateSum($data->clientId) )
                                        <td class="btn btn-outline-success">0 {{$currency}}</td>
                                      @else
                                        <td class="btn btn-outline-danger">{{number_format($data->total_ht - $data->CalculateSum($data->clientId))}} {{$currency}}</td>
                                      @endif
                                  @endif
                                 
                                </tr>
                              </tbody>
                            </table>
                             @if($data->tva !="")
                                @if ($data->montantPay < $data->total_ttc)
                                <form action="{{ route("services.update", [$data->id]) }}" method="post">
                                  @csrf
                                  @method("PUT")
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label for="montantPay">Montant</label>
                                        <input type="number" name="montantPay" id="montantPays" value="" class="form-control @error('montantPay') is-invalid @enderror"  placeholder="">
                                        @error("montantPay")
                                            <span class="text-danger"> {{$message}}</span>
                                        @enderror
                                    </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                    <input type="hidden" name="clientId" value="{{$data->clientId}}">
                                    <input type="hidden" name="types" value="SERVICE">
                                    <input type="hidden" name="option" value="SERVICE">
                                  </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                                  <button type="submit" class="btn btn-outline-primary rounded-pill">Confirmer</button>
                                </div>
                              </form>
                              @endif
                            @else
                              @if ($data->montantPay < $data->total_ht)
                                <form action="{{ route("services.update", [$data->id]) }}" method="post">
                                  @csrf
                                  @method("PUT")
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label for="montantPay">Montant</label>
                                        <input type="number" name="montantPay" id="montantPays" value="" class="form-control @error('montantPay') is-invalid @enderror"  placeholder="">
                                        @error("montantPay")
                                            <span class="text-danger"> {{$message}}</span>
                                        @enderror
                                    </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                    <input type="hidden" name="clientId" value="{{$data->clientId}}">
                                    <input type="hidden" name="types" value="SERVICE">
                                    <input type="hidden" name="option" value="SERVICE">
                                  </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                                  <button type="submit" class="btn btn-outline-primary rounded-pill">Confirmer</button>
                                </div>
                              </form>
                              @endif
                            @endif
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                  <!-- /.modal -->

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
                              <form action="{{route('printService')}}" method="get">
                                @csrf
                                <input type="hidden" name="option" value="SERVICE" id="">
                                <input type="hidden" name="clientId" value="{{$data->clientId}}" id="">
    
                              <div class="modal-body">
                                <p>
                                  Voulez vous impremer ce enregistrement ?
                                </p>
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
                              <form action="{{ route("services.destroy", [$data->id]) }}" method="post">
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
