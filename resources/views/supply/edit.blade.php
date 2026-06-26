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
      <form action="{{ route("commande.update", [$data->id]) }}" method="post">
      @csrf
      @method("PUT")
      <div class="card-body">

        <div class="row g-3">
        <div class="col-md-6">

          <div class="form-group">
            <label for="numero">Fournisseurs</label>
            <select name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" >
              <option value="">...</option>
              @if(count($fours))
                @foreach ($fours as $four)
                  <option value="{{$four->id}}" {{($four->id == $data->numero) ? 'selected' : ''}}>{{$four->nom_fournisseur}}</option>
                @endforeach
              @endif
            </select>
            @error("numero")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-6">

        <div class="form-group">
          <label for="operation">Opération </label>
          <select name="operation" id="operation" class="form-control @error('operation') is-invalid @enderror" >
            <option value="Importer" {{($data->operation=="Importer") ? 'selected' : ''}}>Importer</option>
          </select>
          @error("operation")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
  </div>
  <div class="row g-3">
    <div class="col-md-3">

      <div class="form-group">
        <label for="libelle">Libelle</label>
        <input type="text" name="libelle" value="{{$data->libelle}}" class="form-control @error('libelle') is-invalid @enderror" id="libelle" placeholder="" >
        @error("libelle")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
        <div class="col-md-3">

          <div class="form-group">
            <label for="prix">Prix Unitaire</label>
            <input type="number" name="prix" value="{{$data->prix}}" onblur="CalculateMontant()" class="form-control @error('prix') is-invalid @enderror" id="prix" placeholder="" >
            @error("prix")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
        
      <div class="col-md-3">

        <div class="form-group">
          <label for="qte_commander">Qté </label>
          <input type="number" name="qte_commander" value="{{$data->qte_commander}}" onblur="CalculateMontant()" class="form-control @error('qte_commander') is-invalid @enderror" id="qte" placeholder="" >
          @error("qte_commander")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      <div class="col-md-3">

        <div class="form-group">
          <label for="total">Montant (Solde)</label>
          <input type="number" name="total" value="{{$data->total}}" class="form-control @error('total') is-invalid @enderror" id="soldes" placeholder="" readonly >
          @error("total")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>
        <div class="row g-3">

      <div class="col-md-5">

        <div class="form-group">
          <label for="dates">Date </label>
          <input type="date" name="dates" value="{{$data->dates}}" class="form-control @error('dates') is-invalid @enderror" id="dates" placeholder="" >
          @error("dates")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>

    <div class="col-md-7">

      <div class="form-group">
        <label for="done_by">Effectuer Par</label>
        <input type="text" name="done_by" value="{{$data->done_by}}" class="form-control @error('done_by') is-invalid @enderror" id="done_by" placeholder="" >
        @error("done_by")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>

  </div>

  <div class="row g-3">
          <div class="col-md-12">

          <div class="form-group">
            <label for="descs">Les commentairs</label>
            <textarea name="descs" id="descs" class="form-control @error('descs') is-invalid @enderror" cols="20" rows="2" placeholder="Reparation de voiture 30000; Essence pour les motos 40000;">{{$data->descs}}</textarea>
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
        <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("commande.index") }}"> Annuler</a>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section("scripts")
  <script>

    function CalculateMontant() {
      var qte=parseInt(document.getElementById("qte").value);
      var prix=parseInt(document.getElementById("prix").value);

      if (typeof qte === "number" && !isNaN(qte) && typeof prix === "number" && !isNaN(prix)) {
        document.getElementById("soldes").value=qte * prix;
      }else{
        document.getElementById("soldes").value="";
      }
 
    }

  </script>
@endsection
