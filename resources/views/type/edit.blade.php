@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'un Tyoe</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("type.update", [$datas->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">
<div class="row">
  <div class="col-md-6">
          <div class="form-group">
            <label for="nom_type">Sous Categorie</label>
            <input type="nom_type" name="nom_type" value="{{$datas->nom_type}}" class="form-control @error('nom_type') is-invalid @enderror" id="nom_type" placeholder="">
            @error("nom_type")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="categorie">Categorie</label>
          <select name="categorie" id="" class="form-control @error('categorie') is-invalid @enderror">
            <option value="">...</option>
            @if (count($cats))
              @foreach ($cats as $cat)
                <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
              @endforeach
            @endif
        </select>
        
          @error("categorie")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>

    </div>
    </div>

        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Modifier</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("type.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
