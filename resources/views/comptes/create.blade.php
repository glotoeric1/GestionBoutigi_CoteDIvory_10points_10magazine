@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'un Compte</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("comptes.store") }}" method="post">
      @csrf
        <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">

            <div class="form-group">
              <label for="numero">Numéro de compte</label>
              <input type="numero" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" placeholder="" >
              @error("numero")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
          </div>
          <div class="col-md-6">

            <div class="form-group">
              <label for="bank">Banque</label>
              <input type="bank" name="bank" class="form-control @error('bank') is-invalid @enderror" id="bank" placeholder="" >
              @error("bank")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
           </div>
          </div>
        <div class="row g-3">
          <div class="col-md-6">

            <div class="form-group">
              <label for="titulaire">Titulaire de compte</label>
              <input type="titulaire" name="titulaire" class="form-control @error('titulaire') is-invalid @enderror" id="numero" placeholder="" >
              @error("titulaire")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
          </div>
          <div class="col-md-6">

            <div class="form-group">
              <label for="type">Type de compte</label>
               <select name="type" id="types" class="form-control @error('type') is-invalid @enderror" >
                  <option value="">...</option>
                  <option value="Courant" {{(old('type')=="Courant") ? "selected" : ''}}>Compte Courant</option>
                  <option value="Epargne" {{(old('type')=="Epargne") ? 'selected' : ''}}>Compte d'epargne</option>
                  <option value="Caisse" {{(old('type')=="Caisse") ? 'selected' : ''}}>Caisse</option>
                  <option value="Autre" {{(old('type')=="Autre") ? 'selected' : ''}}>Autre</option>
              </select>
              @error("type")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
          </div>
          </div>

        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("comptes.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
