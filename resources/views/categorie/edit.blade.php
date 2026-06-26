@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'une Catégorie</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("categorie.update", [$datas->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">
          <div class="form-group">
            <label for="nom_categorie">Catégorie</label>
            <input type="nom_categorie" name="nom_categorie" value="{{$datas->nom_categorie}}" class="form-control @error('nom_categorie') is-invalid @enderror" id="nom_categorie" placeholder="">
            @error("nom_categorie")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Modifier</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("categorie.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
