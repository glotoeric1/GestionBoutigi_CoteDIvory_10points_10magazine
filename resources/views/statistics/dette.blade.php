@extends("layout.main")
@section("main")
@php
  $cc="Fcfa";
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
      <form action="{{ route("seach.statistics") }}" method="get" class="form-control">
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
          <label for="montant" class="form-label">Produit</label>
          <select name="id_prod" id="id_categorie" class="form-control @error('id_categorie') is-invalid @enderror">
            <option value="">...</option>
            @if (count($produits)>0)
              @foreach ($produits as $produit)
              <option value="{{$produit->id}}">{{$produit->nom_produit}}</option>
              @endforeach
            @endif
          </select>
          
          @error("id_prod")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
  
      </div>
      </div>
        <div class="gap-2 d-md-flex d-md-block justify-content-center">
          <button type="submit" name="option" value="SEARCH"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche</button>
          <button type="submit" name="option" value="PRINT"  class="btn btn-outline-primary mx-2 px-5 rounded-pill">Recherche & Imp</button>
            <button type="reset" class="btn btn-outline-warning mx-2  px-5 rounded-pill">Annuler</button>
        </div>
    </form>
  
  </div>
    <!-- /.card-header -->
    <div class="card-body mt-5">
      <a href="{{route("detteCreate")}}" class="btn btn-outline-success mb-2">Total qté en Dette : {{$totalQ}} </a>
      <a href="{{route("detteCreate")}}" class="btn btn-outline-success mb-2">Total Montant : {{number_format($totalM)}} {{$cc}}</a>
      @if ($totalR !="")
      <a href="{{route("venteCreate")}}" class="btn btn-outline-success mb-2">Total Reste à payer : {{number_format($totalM-abs($totalR))}} {{$cc}}</a>
      <a href="{{route("detteCreate")}}" class="btn btn-outline-success mb-2">Total Restant : 
        {{number_format(abs($totalR))}} {{$cc}}  
      </a>
      @endif
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Numéro de client</th>
                    <th>Nom</th>
                    <th>Nom Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Montant</th>
                    <th>Date</th>

                </tr>
            </thead>
            <tbody>
            @if(count($datas)>0)
            @foreach($datas as $data)
             <tr>
                    <td>{{ $data->clientId }}</td>
                    <td>{{ $data->nom }}</td>
                    <td>{{ $data->nom_produit }}</td>
                    <td>{{ number_format($data->prix) }}{{$cc}}</td>
                    <td>{{ $data->quantite }} </td>
                    <td>{{ number_format($data->montant) }}{{$cc}}</td>
                    <td>{{ $data->FormatDate($data->created_at) }}</td>
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

