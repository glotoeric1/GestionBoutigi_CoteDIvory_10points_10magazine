@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'une barcode </h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("barcode.store") }}" method="post">
      @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="code_max">barcode</label>
            <input type="number" name="code_max" class="form-control @error('code_max') is-invalid @enderror" id="code_max" placeholder="" >
            @error("code_max")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" name="types" value="NON" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("barcode.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
