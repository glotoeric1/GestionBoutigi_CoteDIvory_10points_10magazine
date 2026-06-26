@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Opération bancaire</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("banks.store") }}" method="post">
      @csrf
        <div class="card-body">
          <div class="row g-3">
          <div class="col-md-3"></div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="types">Type de compte </label>
                <select name="numero_de_compte" id="" class="form-control  @error('numero_de_compte') is-invalid @enderror">
                    <option value=""></option>
                    @if (count($banks)>0)
                      @foreach ($banks as $data)
                        <option value="{{$data->numero}}" {{(old('numero_de_compte')==$data->numero) ? 'selected' : ''}}>{{$data->numero}} - {{ $data->bank}} - {{ $data->titulaire}}</option>
                      @endforeach
                    @endif
                </select>
              @error("numero_de_compte")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>

        <div class="col-md-3"></div>
      </div>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="form-group">
                <label for="operation">Opération </label>
                <select name="operation" id="operation" class="form-control @error('operation') is-invalid @enderror" >
                  <option value="">...</option>
                  <option value="Dépôt" {{(old('operation') =="Dépôt")  ? 'selected' : ''}}>Dépôt</option>
                  <option value="Retrait" {{(old('operation') =="Retrait")  ? 'selected' : ''}}>Retrait</option>
                  <option value="Remise" {{(old('operation') =="Remise")  ? 'selected' : ''}}>Remise</option>
                </select>
                @error("operation")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
          </div>

          <div class="col-md-3">

            <div class="form-group">
              <label for="montant">Montant </label>
              <input type="number" name="montant" value="{{old('montant')}}" class="form-control @error('montant') is-invalid @enderror" id="montant" placeholder="" >
              @error("montant")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>

        <div class="col-md-3">

          <div class="form-group">
            <label for="dates">Date </label>
            <input type="date" name="dates" value="{{old('dates')}}" class="form-control @error('dates') is-invalid @enderror" id="dates" placeholder="" >
            @error("dates")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>

      <div class="col-md-3">

        <div class="form-group">
          <label for="done_by">Effectuer Par</label>
          <input type="text" value="{{old('done_by')}}" name="done_by" class="form-control @error('done_by') is-invalid @enderror" id="done_by" placeholder="" >
          @error("done_by")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>

    </div>

    <div class="row g-3">
            <div class="col-md-12">

            <div class="form-group">
              <label for="contact">Les Détails</label>
              <textarea name="descs" id="descs" class="form-control @error('descs') is-invalid @enderror" cols="20" rows="2" placeholder="Reparation de voiture 30000; Essence pour les motos 40000;">{{old('descs')}}</textarea>
              @error("descs")
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
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("banks.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
