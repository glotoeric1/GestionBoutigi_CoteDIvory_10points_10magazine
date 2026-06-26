@extends("layout.main")
@section("main")
@php
  $cc='Fcfa';
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Créances</h3>
        <a class="btn btn-outline-primary float-right rounded-pill mx-2" href="{{ route("dette.create") }}"> <i class="fa fa-plus"></i> Ajouter</a>
        <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
        <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()" id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>

        
    </div>
    <!-- -->
    <div id="form" class="mb-5 d-none">
      <form action="{{ route("seach.Item") }}" method="get" class="form-control">
        @csrf
        <input type="hidden" name="types" id="" value="DETTES">
      <div class="row g-3">
        <div class="col-md-3">
            <div class="form-group">
              <label for="montant" class="form-label">Date debut</label>
              <input type="date" name="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" placeholder="">
              @error("dateDebut")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="montant" class="form-label">Date fin</label>
            <input type="date" name="dateFin" class="form-control  @error('dateFin') is-invalid @enderror" id="dateFin" placeholder="">
            @error("dateFin")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="montant" class="form-label">Vendeur(us)</label>
          <select name="username" id="username" class="form-control @error('username') is-invalid @enderror">
            <option value="">...</option>
            @if (count($users)>0)
              @foreach ($users as $user)
              <option value="{{$user->id}}">{{$user->name}}</option>
              @endforeach
            @endif
          </select>
          
          @error("username")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="montant" class="form-label">Catégorie</label>
          <select name="categorie" id="id_categorie" class="form-control @error('id_categorie') is-invalid @enderror">
            <option value="">...</option>
            @if (count($cats)>0)
              @foreach ($cats as $cat)
              <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
              @endforeach
            @endif
          </select>
          
          @error("montantApayer")
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
      <a href="{{route("dette.index")}}" class="btn btn-outline-success mb-2">Total qté en Dette : {{number_format($totalQ)}} </a>
      <a href="{{route("dette.index")}}" class="btn btn-outline-success mb-2">Total Montant : {{number_format($totalM)}}{{$cc}}</a>
      @if ($totalR != "")
        <a href="{{route("venteCreate")}}" class="btn btn-outline-success mb-2">Total Payé : {{number_format($totalM-abs($totalR))}}{{$cc}}</a>
        <a href="{{route("dette.index")}}" class="btn btn-outline-success mb-2">Total Restant : {{number_format(abs($totalR))}}{{$cc}}</a>
      @endif
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th width="70">Nº Client</th>
                    <th>Nom</th>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Qté</th>
                    <th>Total</th>
                    <th>Reste à payer </th>
                    <th width="120">Action</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
              <td>{{ $data->FormatDate($data->created_at) }}</td>
              @if($data->tva=="")
                  @if ($data->montantDonner < $data->total_ht )
                      <td class="bg-danger" title="Le Restant : {{number_format($data->total_ht-$data->montantDonner)}}{{$cc}}">
                         <a href="{{ route('dette.showByClient', [$data->clientId]) }}">
                        {{ $data->clientId }}
                        </a>
                      </td>
                  @else
                      <td>
                        <a href="{{ route('dette.showByClient', [$data->clientId]) }}">
                        {{ $data->clientId }}
                        </a>
                      </td> 
                  @endif
              @else
                  @if ($data->montantDonner < $data->total_ttc )
                      <td class="bg-danger" title="Le Restant : {{number_format($data->total_ttc-$data->montantDonner)}}{{$cc}}">
                          <a href="{{ route('dette.showByClient', [$data->clientId]) }}">
                        {{ $data->clientId }}
                          </a>
                      </td>
                  @else
                      <td>
                         <a href="{{ route('dette.showByClient', [$data->clientId]) }}">
                        {{ $data->clientId }}
                          </a>
                      </td> 
                  @endif
              @endif
                    <td>{{ $data->nom }}</td>
                    <td>{{ $data->nom_produit }}</td>
                    <td>{{ number_format($data->prix) }} {{$cc}}</td>
                    <td>{{ $data->quantite }} </td>
                      @if($data->tva=="")
                        <td>{{ number_format($data->total_ht) }} {{$cc}}</td>
                      @else
                        <td title="@if($data->tva=='0.05') TVA : 5% @else TVA : 18% @endif ">{{ number_format($data->total_ttc) }} {{$cc}}
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info ml-1">
                        @if ($data->tva=="0.05") 5% @else 18% @endif
                        </td>
                      @endif
                     @if($data->tva=="")
                        <td class="btn btn-outline-danger">{{ number_format($data->total_ht - $data->montantDonner)  }} {{$cc}}</td>
                      @else
                        <td class="btn btn-outline-danger">{{ number_format($data->total_ttc - $data->montantDonner)  }} {{$cc}}
                        </td>
                      @endif
                      <td>
                    <!-- Activer -->
                    <a data-toggle="modal" data-target="#pay{{$data->id}}" href="#">
                        <i class="fas fa-edit px-1 text-outline-primary"></i>
                    </a>

                  <!-- /.modal -->

                    <div class="modal fade" id="pay{{$data->id}}">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header modal-head">
                            <h4 class="modal-title ">Paiement - {{$data->nom}} </h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <span style="font-size: 16px;"> Fait le : {{$data->created_at->format("d/m/Y")}} à {{$data->created_at->format("h:i")}} </span>
                            <div class="row">
                              <div class="col-md-12">
                                <table class="table table-bordered border-primary">
                                  <thead>
                                    <tr>
                                      <th>Total</th>
                                      <th>Montant déja payer</th>
                                      <th>Restant (a payer)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                       @if($data->tva=="")
                                          <td>{{number_format($data->total_ht)}}  {{$cc}}</td>
                                        @else
                                          <td>{{number_format($data->total_ttc)}}  {{$cc}}</td>
                                        @endif
                                      <td>{{number_format($data->montantDonner)}}  {{$cc}}</td>
                                    @if($data->tva=="")
                                        @if ($data->total_ht <= $data->montantDonner )
                                          <td class="btn btn-outline-success">0  {{$cc}}</td>
                                        @else
                                          <td class="btn btn-outline-danger">{{number_format($data->total_ht- $data->montantDonner)}}  {{$cc}}</td>
                                        @endif
                                     @else
                                            @if ($data->total_ttc <= $data->montantDonner )
                                              <td class="btn btn-outline-success">0  {{$cc}}</td>
                                            @else
                                              <td class="btn btn-outline-danger">{{number_format($data->total_ttc- $data->montantDonner)}}{{$cc}}</td>
                                            @endif
                                     @endif
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <form action="{{ route("dette.update", [$data->id]) }}" method="post">
                              @csrf
                              @method("PUT")
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="montant">Montant</label>
                                  <input type="hidden" name="clientId" value="{{$data->clientId}}">
                                  <input type="number" name="montant" id="montants" value="" class="form-control @error('montant') is-invalid @enderror"  placeholder="">
                                  @error("montant")
                                      <span class="text-danger"> {{$message}}</span>
                                  @enderror
                              </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label for="montant">Comments</label>
                                  <textarea name="comments" id="" class="form-control montantPayer" cols="1" rows="2"></textarea>
                              </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-success rounded-pill">Confirmer</button>
                          </div>
                        </form>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                <!-- /.modal -->


                    <!-- activer -->
                    <a data-toggle="modal" id="idvente" data-target="#bal{{$data->clientId}}" href="#">
                        <i class="fas fa-eye px-1 text-info"></i>
                    </a>
                    <input type="hidden" value="{{$data->clientId}}" name="idcl" class="idcl">
                  <!-- /.modal -->

                    <div class="modal fade" id="bal{{$data->clientId}}">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header modal-head">
                            <h4 class="modal-title ">
                              <h4>Nom : {{ $data->nom}} </h4>
                            </h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <h4>Contact : {{ $data->contact}} <br> ID : {{ $data->clientId}} </h4> <hr>

                              <h5 class="float-end">
                                @if ($data->tva=="")
                                  Total HT : {{ number_format($data->total_ht)}} {{$cc}}<br> 
                                @else
                                  TVA : @if ($data->tva=="0.05") 5% @else 18% @endif <br>
                                  Total HT : {{ number_format($data->total_ht)}} {{$cc}}<br> 
                                  Total TVA : {{ number_format($data->total_tva)}} {{$cc}}<br> 
                                  Total TTC : {{ number_format($data->total_ttc)}} {{$cc}}<br>  
                                @endif
                                
                                Montant Donner : {{ number_format($data->montantDonner)}}  {{$cc}}<br> 
                              @if($data->tva=="") 
                                @if ($data->total_ht <= $data->montantDonner )
                                  Le Restant : <span class="text-success">0  {{$cc}} </span>
                                @else
                                  Le Restant : <span class="text-danger">{{ number_format($data->total_ht- $data->montantDonner)}}  {{$cc}} </span> <br>
                                  @php
                                    $today = \Carbon\Carbon::now();
                                    $difference = $today->diffInDays($data->dateApayer, false)
                                  @endphp

                                  À payer le : {{date('d-m-Y', strtotime($data->dateApayer))}} <br>
                                <span class="text-danger">
                                  @if( $difference <0)
                                    Retard de : {{ abs( $difference = $today->diffInDays($data->dateApayer, false))}} Jour (s)
                                  @endif
                                </span>
                                @endif
                               @else
                                @if ($data->total_ttc <= $data->montantDonner )
                                  Le Restant : <span class="text-success">0  {{$cc}} </span>
                                @else
                                  Le Restant : <span class="text-danger">{{ number_format($data->total_ttc- $data->montantDonner)}}  {{$cc}} </span> <br>
                                  @php
                                    $today = \Carbon\Carbon::now();
                                    $difference = $today->diffInDays($data->dateApayer, false)
                                  @endphp

                                À payer le : {{date('d-m-Y', strtotime($data->dateApayer))}} <br>
                                <span class="text-danger">
                                  @if( $difference <0)
                                    Retard de : {{ abs( $difference = $today->diffInDays($data->dateApayer, false))}} Jour (s)
                                  @endif
                                </span>
                                @endif
                               @endif
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

                    @if (auth()->user()->roles=="Admin")
                          <!-- activer -->
                           @if ($data->tva !="")
                              @if ($data->montantDonner >= $data->total_ttc)
                                  <a data-toggle="modal" data-target="#del{{$data->id}}" href="#">
                                    <i class="fas fa-trash px-1 text-danger"></i>
                                </a>
                              @endif
                            @else
                              @if ($data->montantDonner >= $data->total_ht)
                                  <a data-toggle="modal" data-target="#del{{$data->id}}" href="#">
                                    <i class="fas fa-trash px-1 text-danger"></i>
                                </a>
                              @endif
                            @endif
    
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
                               <form action="{{route("dette.deleteAfterBy", [$data->id, $data->clientId])}}" method="POST">
                                  @csrf
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
                    <a data-toggle="modal" data-target="#print{{$data->id}}" href="#">
                      <i class="fas fa-print px-1 text-info"></i>
                    </a>

                    <!-- /.modal -->
    
                    <div class="modal fade" id="print{{$data->id}}">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header modal-head">
                            <h4 class="modal-title ">Imprission </h4>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="{{route('printDette')}}" method="get">
                            @csrf
                            <input type="hidden" name="option" value="DETTE" id="">
                            <input type="hidden" name="clientId" value="{{$data->clientId}}" id="">

                          <div class="modal-body">
                            <p>
                             Voulez vous imprimer ce enregistrement ?
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

