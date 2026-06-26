@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'un Fournisseur</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("fournisseur.update", [$datas->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">

          <div class="form-group">
            <label for="nom_fournisseur">Fournisseur</label>
            <input type="nom_fournisseur" name="nom_fournisseur" value="{{$datas->nom_fournisseur}}" class="form-control @error('nom_fournisseur') is-invalid @enderror" id="exampleInputEmail1" placeholder="" >
            @error("nom_fournisseur")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Adresse</label>
            <input type="adresse_fournisseur" name="adresse_fournisseur" value="{{$datas->adresse_fournisseur}}" class="form-control @error('adresse_fournisseur') is-invalid @enderror" id="exampleInputPassword1" placeholder="">
            @error("adresse_fournisseur")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Contact</label>
            <input type="contact_fournisseur" name="contact_fournisseur" value="{{$datas->contact_fournisseur}}" class="form-control @error('contact_fournisseur') is-invalid @enderror" id="exampleInputPassword1" placeholder="">
            @error("contact_fournisseur")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
          <div class="form-group">
            <label for="exampleInputPassword1">E-mail</label>
            <input type="email_fournisseur" name="email_fournisseur" value="{{$datas->email_fournisseur}}" class="form-control @error('email_fournisseur') is-invalid @enderror" id="email" placeholder="">
            @error("email_fournisseur")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Modifier</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("fournisseur.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
