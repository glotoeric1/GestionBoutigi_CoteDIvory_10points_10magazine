@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'une depense</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("depenses.update", [$data->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">
      <h3 class="text-center rounded border border-5 border-success py-2">Montant dans la Caisse : {{number_format($total)}} F cfa</h3>
          <div class="row g-3">
            <div class="col-md-12">

              <div class="form-group">
                <label for="titre">Sujet </label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{$data->titre}}" id="titre" placeholder="" >
                @error("titre")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
          </div>
        </div>
          <div class="row g-3">
            <div class="col-md-12">
            <input type="hidden" name="numero" value="{{$data->numero }}"> 
            <input type="hidden" name="numero_de_compte" value="{{$numero_de_compte ?? old('numero_de_compte')}}" id=""> 
            <input type="hidden" name="caisse" value="{{$total ?? old('caisse')}}" id="">
            <div class="form-group">
              <label for="contact">Les Détails</label>
              <textarea name="descs" id="descs" class="form-control @error('descs') is-invalid @enderror" cols="20" rows="3" placeholder="Reparation de voiture 30000; Essence pour les motos 40000;">{{$data->descs}}</textarea>
              @error("descs")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
      </div>
        <div class="row g-3">
        <div class="col-md-4">

          <div class="form-group">
            <label for="montant">Montant Total</label>
            <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" value="{{$data->montant}}" id="montant" placeholder="" >
            @error("montant")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="dates">Date Fait</label>
          <input type="date" name="dates" class="form-control @error('dates') is-invalid @enderror" value="{{$data->dates}}" id="dates" placeholder="" >
          @error("dates")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">

      <div class="form-group">
        <label for="done_by">Effectuer Par</label>
        <input type="text" name="done_by" class="form-control @error('done_by') is-invalid @enderror" value="{{$data->done_by}}" id="done_by" placeholder="" >
        @error("done_by")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
  </div>

  </div>
    </div>

        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("depenses.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
