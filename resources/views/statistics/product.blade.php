@extends("layout.main")
@section("main")
@php
  $cc="Fcfa";
  $qteEnStock=0;
  $qteVendue=0;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Produits</h3>
        <a class="btn btn-outline-primary rounded-pill float-right" href="{{ route("produit.create") }}"> <i class="fa fa-plus"></i> Ajouter</a>
        <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen"> <i class="fas fa-search"></i> Recherche Avancé </a>
        <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none " onclick="closeForm()" id="btnClose"> <i class="fas fa-minus"></i> Fermer</a>
   
    </div>
    <!-- -->
    <div id="form" class="mb-5 d-none">
      <form action="{{ route("seach.statistics") }}" method="get" class="form-control">
        @csrf
        <input type="hidden" name="types" id="" value="PRODUIT">
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
          <label for="montant" class="form-label">Utilisateur</label>
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
          <label for="montant" class="form-label">Opération</label>
          <select name="op" id="op" class="form-control @error('op') is-invalid @enderror" required>
            <option value="">...</option>
            <option value="VENTE">VENTE</option>
            <option value="DETTE">DETTE</option>
            <option value="AVANCE">AVANCE</option>
          </select>
          @error("op")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
      </div>
      </div>
        <div class="gap-2 d-md-flex d-md-block justify-content-center">
            <button type="submit"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
            <button type="submit" name="print" value="PRINT"  class="btn btn-outline-primary mx-2 px-5 rounded-pill d-none">Recherche & Impr</button>
            <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
        </div>
    </form>
  </div>
    <!-- /.card-header -->
    <div class="card-body mt-5">
      <a href="{{route("stockCreate")}}" class="btn btn-outline-success mb-2 px-2 ">Total qté en stock : {{number_format($totalQ)}} </a>
        <a href="{{route("stockCreate")}}" class="btn btn-outline-success mb-2 px-2 ">Total qté Vendue : {{number_format($totalV)}} </a>
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom de Produit</th>
                    <th>Quantité en stock</th> 
                    <th>Quantité vendue</th> 
                    <th>Vendeur</th>
                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
              @php
                $qteEnStock =$data->quantite;
              @endphp
                    <td>{{ $data->formatDate($data->created_at) }}</td>
                    <td>{{ $data->nom_produit }}</td>
                    <td>{{ $qteEnStock }}</td>
                    @if(count($ventes)>0)
                      @foreach($ventes as $vente)
                        @if($data->id==$vente->id_prod)
                          @php $qteVendue += $vente->quantite; @endphp
                        @endif
                      @endforeach
                      <td>{{ $qteVendue }}</td>
                      <td>{{ $vente->ShowUserNameVente($vente->username) }}</td>
                    @else 
                      <td>0</td>
                      <td>Non Disponible</td>
                    @endif
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
