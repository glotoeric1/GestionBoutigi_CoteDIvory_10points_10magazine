@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'un vente Indirect</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("venteIndirects.store") }}" method="post">
      @csrf
        <div class="card-body">

          <div class="row g-3">
          <div class="col-md-12">
            <label for="nom">Prénom et Nom du Client</label>
              <div class="form-group input-group">
                <select name="nom" class="form-control code_barre select2 @error('nom') is-invalid @enderror" id="nom">
                  <option value=""></option>
                    @if (count($clients))
                      @foreach ($clients as $client)
                        <option value="{{$client->contact}};{{$client->nom}}">{{$client->nom}} - {{$client->contact}}</option>
                      @endforeach
                    @endif
                </select>
                <a class="btn btn-outline-primary code_barre" title="Ajouter au stock" data-toggle="modal" data-target="#clientAdd" href="#">
                    <i class="fas fa-plus px-1"></i>
                </a> 
                @error("nom")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
        </div>
        </div>
        <hr>

          <div class="row g-3">
          <div class="col-md-12">
              <label for="contact">Produit</label>
            <div class="form-group input-group">
              <select name="produit" class="form-control code_barre select2 @error('produit') is-invalid @enderror">
                <option value=""></option>
                @if (count($pros))
                  @foreach ($pros as $pro)
                    <option value="{{$pro->libelle}}">{{$pro->libelle}}</option>
                  @endforeach
                @endif
              </select>
                <a class="btn btn-outline-primary code_barre" title="Ajouter au stock" data-toggle="modal" data-target="#prodAdd" href="#">
                    <i class="fas fa-plus px-1"></i>
                </a> 
              @error("produit")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        </div>
        <div class="row g-3">
          <div class="col-md-3">

            <div class="form-group">
              <label for="montant">Prix Init</label>
              <input type="text" name="prix_init" class="form-control montant @error('prix_init') is-invalid @enderror" id="prix_init" placeholder="" >
              @error("prix_init")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-3">

            <div class="form-group">
              <label for="montant">Prix</label>
              <input type="text" name="montant" class="form-control montant @error('montant') is-invalid @enderror" id="montant" placeholder="" >
              @error("montant")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-2">

          <div class="form-group">
            <label for="qte">Quantité</label>
            <input type="number" step="0.01" name="qte" onblur="calculateMontant()" class="form-control montant @error('qte') is-invalid @enderror" id="qte" placeholder="" >
            @error("qte")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="total">Total Montant à payer</label>
          <input type="text" name="total_ht"  onblur="calculateMontant()" class="form-control montant @error('total_ht') is-invalid @enderror" id="total" placeholder="" readonly >
          @error("total_ht")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>

      <div class="row g-3">
        <div class="col-md-4">

          <div class="form-group">
            <label for="tva">TVA</label>
            <select name="tva_id" id="tva_id" class="form-control montant @error('tva') is-invalid @enderror">
                <option value=""></option>
                <option value="0.05">5%</option>
                <option value="0.18">18%</option>
            </select>
            @error("tva")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

          <div class="form-group">
            <label for="total_tva">Total TVA</label>
            <input type="text" name="total_tva" onblur="calculateMontant()" class="form-control montant @error('total_tva') is-invalid @enderror" id="total_tva" placeholder="" readonly>
            @error("total_tva")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

          <div class="form-group">
            <label for="montant">Total TTC</label>
            <input type="text" name="total_ttc" onblur="calculateMontant()" class="form-control montant @error('total_ttc') is-invalid @enderror" id="total_ttc" placeholder="" readonly>
            @error("total_ttc")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    </div>
      <div class="row g-3">
        <div class="col-md-12">

          <div class="form-group">
            <label for="montant">Montant donné par client</label>
            <input type="text" name="montantPay" onblur="calculateMontant()" class="form-control montant @error('montantPay') is-invalid @enderror" id="montantPay" placeholder="">
            @error("montantPay")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    </div>

      <div class="row g-3">
        <div class="col-md-12">

          <div class="form-group">
            <label for="years">Les commentaires (Optional)</label>
            <textarea name="descs" id="descs"  class="form-control montant @error('descs') is-invalid @enderror" cols="30" rows="2"></textarea>
        </div>
    </div>
    </div>

        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" name="save" value="SAVED" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <button type="submit" name="print" value="PRINT" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer & Impr</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("venteIndirects.index") }}"> Annuler</a>
        </div>
      </form>
    </div>

        <!-- /.modal -->
          <div class="modal fade" id="clientAdd">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header modal-head">
                  <h4 class="modal-title ">Ajouter un Client</h4>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route("client.store") }}" method="post">
                  @csrf
                  <input type="hidden" name="types" value="INDIRECT">
                <div class="modal-body">
                  <div class="row mt-2">
                    <div class="col-md-12">
                      <label for="nom">Prénom et Nom de client</label>
                      <input type="text" name="nom" value="" class="form-control @error('nom') is-invalid @enderror">
                    </div>
                    <div class="col-md-12">
                      <label for="contact">Contact de client</label>
                      <input type="text" name="contact" value="" class="form-control @error('contact') is-invalid @enderror">
                    </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                </div>
              </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
      <!-- /.modal -->

      <!-- /.modal -->
          <div class="modal fade" id="prodAdd">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header modal-head">
                  <h4 class="modal-title ">Ajouter Produit</h4>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route("stcoks.add") }}" method="post">
                  @csrf
                <div class="modal-body">
                  <div class="row mt-2">
                    <div class="col-md-12">
                      <label for="numero_charge">Libelle</label>
                      <input type="text" name="libelle" value="" class="form-control @error('numero_charge') is-invalid @enderror">
                    </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
                  <button type="submit" class="btn btn-outline-success rounded-pill">Confirmer</button>
                </div>
              </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
      <!-- /.modal -->

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
    var montant= parseFloat(document.getElementById("montant").value);
    var qte= parseFloat(document.getElementById("qte").value);
    var total=document.getElementById("total");
    var restant=document.getElementById("total");

    if (typeof montant==="number" && !isNaN(montant) && typeof qte==="number" && !isNaN(qte)) {
      restant.value=montant*qte;
    }else{
      restant.value="";
    }

    //calculateBalance();

  }
  </script>
@endsection
