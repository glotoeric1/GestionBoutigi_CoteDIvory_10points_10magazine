@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification - Paiement d'avance</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("services.update", [$data->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">
          <input type="hidden" name="clientId" id="" value="{{$data->clientId}}">
          <input type="hidden" name="types" id="" value="SERVICE">
          <div class="row g-3">
          <div class="col-md-6">
              <div class="form-group">
                <label for="nom">Nom de Client</label>
                <input type="text" name="nom" value="{{$data->nom}}" class="form-control montant @error('nom') is-invalid @enderror" id="nom" placeholder="" >
                @error("nom")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
              <label for="contact">Numéro de client</label>
              <input type="text" name="contact" value="{{$data->contact}}" class="form-control montant @error('contact') is-invalid @enderror" id="contact" placeholder="" >
              @error("contact")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
      </div>
        </div>
        <hr>

          <div class="row g-3">
          <div class="col-md-12">

            <div class="form-group">
              <label for="contact">Produit</label>
              <select name="titre" id="titre"  onblur="calculateMontant()" class="form-control montant @error('contact') is-invalid @enderror">
                <option value="">...</option>
                @if (count($datas)>0)
                  @foreach ($datas as $dt)
                    <option value="{{$dt->id}}" {{($data->titre==$dt->id) ? 'selected' : ''}}>{{$dt->nom_produit}}</option>
                  @endforeach
                @endif
              </select>
              @error("titre")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        </div>
        <div class="row g-3">
          <div class="col-md-5">

            <div class="form-group">
              <label for="montant">Prix</label>
              <input type="text" name="montant" value="{{$data->montant}}" class="form-control montant @error('montant') is-invalid @enderror" id="montant" placeholder="" readonly>
              @error("montant")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-2">

          <div class="form-group">
            <label for="qte">Quantité</label>
            <input type="number" name="qte" value="{{$data->qte}}" onblur="calculateMontant()" class="form-control montant @error('qte') is-invalid @enderror" id="qte" placeholder="" >
            @error("qte")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-5">

        <div class="form-group">
          <label for="total">Total Montant à payer</label>
          <input type="text" name="total" value="{{$data->total}}" class="form-control montant @error('total') is-invalid @enderror" id="total" placeholder="" readonly >
          @error("total")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">

          <div class="form-group">
            <label for="montant">Montant donné par client</label>
            <input type="text" name="montantPay" value="{{$data->montantPay}}" onblur="calculateMontant()" class="form-control montant @error('montantPay') is-invalid @enderror" id="montantPay" placeholder="">
            @error("montantPay")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    <div class="col-md-6">

      <div class="form-group">
        <label for="restant">Montant Restant à payer</label>
        <input type="text" name="restant" value="{{$data->restant}}" class="form-control montant @error('restant') is-invalid @enderror" id="restant" placeholder="" readonly >
        @error("restant")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>

      <div class="row g-3">
        <div class="col-md-12">

          <div class="form-group">
            <label for="years">Les commentaires</label>
            <textarea name="descs" id="descs"  class="form-control montant @error('descs') is-invalid @enderror" cols="30" rows="2">{{$data->descs}}</textarea>
        </div>
    </div>
    </div>

        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" name="btn" value="SAVED" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <button type="submit" name="option" value="SERVICE" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer & Impr</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("services.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section("scripts")
  <script>
//Get all students information
jQuery('select[name="titre"]').on('change',function(){
    var titre = jQuery(this).val();
    //alert(titre)
    if(titre)
    {
      jQuery.ajax({
          url : '/getproduct/' +titre,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            jQuery.each(data, function(key,value){
                $("#montant").val(value.prix_vente_unitaire);
              });

          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
      $('#montant').val("");
    }
});

  function calculateMontant() {
    var montant= parseInt(document.getElementById("montant").value);
    var qte= parseInt(document.getElementById("qte").value);
    var total=document.getElementById("total");
    var restant=document.getElementById("total");

    if (typeof montant==="number" && !isNaN(montant) && typeof qte==="number" && !isNaN(qte)) {
      restant.value=montant*qte;
    }else{
      restant.value="";
    }

    calculateBalance();

  }

  function calculateBalance() {
    var montantPay= parseInt(document.getElementById("montantPay").value);
    var total= parseInt(document.getElementById("total").value);
    var restant=document.getElementById("restant");

    if (typeof montantPay==="number" && !isNaN(montantPay) && typeof total==="number" && !isNaN(total)) {
      restant.value=total-montantPay;
    }else{
      restant.value="";
    }

  }
  </script>
@endsection
