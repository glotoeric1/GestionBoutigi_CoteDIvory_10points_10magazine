@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajouter un Service</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("services.store") }}" method="post">
      @csrf
        <div class="card-body">

          <div class="row g-3">
          <div class="col-md-11">
              <div class="form-group">
                <label for="nom">Prénom et Nom du Client</label>
              <select name="nom" id="nom" class="form-control montant @error('nom') is-invalid @enderror">
                <option value="">...</option>
                @if (count($clients)>0)
                  @foreach ($clients as $data)
                    <option value="{{$data->contact}};{{$data->nom}}">{{$data->nom}} - {{$data->contact}}</option>
                  @endforeach
                @endif
              </select>
          
                @error("nom")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
        </div>
          <div class="col-md-1">
            <div class="form-group">
              <label for="prix"></label> <br>
              <!-- activer -->
                <a class="btn btn-outline-primary montant mt-2" title="Ajouter au stock" data-toggle="modal" data-target="#addClient" href="#">
                    <i class="fas fa-plus px-1"></i>
                </a>      
          </div>
        </div>
        </div>
        <hr>

          <div class="row g-3">
          <div class="col-md-11">

            <div class="form-group">
              <label for="contact">Service</label>
              <select name="service" id="service" onblur="calculateMontant()" class="form-control montant @error('service') is-invalid @enderror">
                <option value="">...</option>
                @if (count($datas)>0)
                  @foreach ($datas as $data)
                    <option value="{{$data->id}}">{{$data->nom_service}} - {{$data->montant}}</option>
                  @endforeach
                @endif
              </select>
                 <input type="hidden" name="titre" id="titre">
              @error("service")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-1">

          <div class="form-group">
            <label for="prix"></label> <br>
            <!-- activer -->
              <a class="btn btn-outline-primary montant mt-2" title="Ajouter un service" data-toggle="modal" data-target="#addQte" href="#">
                  <i class="fas fa-plus px-1"></i>
              </a>      
        </div>
      </div>
        </div>
        <div class="row g-3">
          <div class="col-md-5">

            <div class="form-group">
              <label for="montant">Prix</label>
              <input type="text" name="montant" class="form-control montant @error('montant') is-invalid @enderror" id="montant2" placeholder="">
              @error("montant")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-2">

          <div class="form-group">
            <label for="qte">Quantité</label>
            <input type="number" name="qte" onblur="calculateMontant()" class="form-control montant @error('qte') is-invalid @enderror" id="qte" placeholder="" >
            @error("qte")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-5">

        <div class="form-group">
          <label for="total">Total Montant à payer</label>
          <input type="text" name="total" class="form-control montant @error('total') is-invalid @enderror" id="total" placeholder="" readonly >
          @error("total")
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
        <div class="col-md-5">

          <div class="form-group">
            <label for="montant">Montant donné par client</label>
            <input type="text" name="montantPay" onblur="calculateMontant()" class="form-control montant @error('montantPay') is-invalid @enderror" id="montantPay" placeholder="">
            @error("montantPay")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <input type="hidden" name="reduction" id="red">
        <div class="col-md-1">
          <div class="form-group">
            <label for="prix"></label> <br>
            <!-- activer -->
              <a class="btn btn-outline-primary montant mt-2" title="Reduction" data-toggle="modal" data-target="#addReduction" href="#">
                  <i class="fas fa-plus px-1"></i>
              </a>      
        </div>
      </div>
    <div class="col-md-6">

      <div class="form-group">
        <label for="restant">Montant Restant à payer</label>
        <input type="text" name="restant" class="form-control montant @error('restant') is-invalid @enderror" id="restant" placeholder="" readonly >
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
            <textarea name="descs" id="descs"  class="form-control montant @error('descs') is-invalid @enderror" cols="30" rows="2"></textarea>
        </div>
    </div>
    </div>

        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" name="saved" value="SAVED" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <button type="submit" name="print" value="PRINT" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer & Impr</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("services.index") }}"> Annuler</a>
        </div>
      </form>
       <!-- /.modal -->
          <div class="modal fade" id="addQte">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header modal-head">
                  <h4 class="modal-title ">Ajouter Service</h4>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route("service.add") }}" method="post">
                  @csrf
                <div class="modal-body">
                  <div class="row mt-2">
                    <div class="col-md-12">
                      <label for="numero_charge">Libelle</label>
                      <input type="text" name="nom_service" value="" id="nom_service" class="form-control @error('nom_service') is-invalid @enderror">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col-md-12">
                      <label for="montant">Montant</label>
                      <input type="number" name="montant" value="" id="montant" class="form-control @error('montant') is-invalid @enderror">
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
</div>

      <!-- /.modal -->
      <div class="modal fade" id="addClient">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header modal-head">
              <h4 class="modal-title ">Ajouter un Fournisseur</h4>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <form action="{{ route("client.store") }}" method="post">
          @csrf
            <div class="modal-body">
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="nom_fournisseur">Prenom et Nom</label>
                 <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" id="nom" placeholder="" >
                  @error("nom")
                      <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="contact_fournisseur">Contact</label>
                  <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror" id="contact" placeholder="" >
                @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-12">
                  <label for="email_fournisseur">Email (optionnel)</label>
                  <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="" >
                @error("email")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
                </div>
              </div>
                <div class="row mt-2">
                <div class="col-md-12">
                  <label for="adresse">Adresse (optionnel)</label>
                  <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" id="adresse" placeholder="" >
                @error("adresse")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
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
      <div class="modal fade" id="addReduction">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header modal-head">
              <h4 class="modal-title ">Ajouter une Reduction</h4>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="success" class="text-center text-success "></p>
              <div class="row mt-2">
                <div class="col-md-10">
                  <label for="reduction">Reduction</label>
                 <input type="text" name="reduction" class="form-control" id="reduction" placeholder="" >
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="prix"></label> <br>
                    <!-- activer -->
                      <a class="btn btn-outline-success mt-2" title="Reduction" onclick="reduction()">
                          <i class="fas fa-check px-1"></i>
                      </a>      
                </div>
              </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  <!-- /.modal -->
@endsection

@section("scripts")
  <script>
//Get all students information
jQuery('select[name="service"]').on('change',function(){
    var titre = jQuery(this).val();
    if(titre)
    {
      jQuery.ajax({
          url : '/getmontant/' +titre,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            jQuery.each(data, function(key,value){
                $("#montant2").val(value.montant);
                $("#titre").val(value.nom_service);
              });

          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
      $('#montant2').val("");
    }
});

  function calculateMontant() {
    var montant= parseInt(document.getElementById("montant2").value);
    var qte= parseInt(document.getElementById("qte").value);
    var total=document.getElementById("total");

    if (typeof montant==="number" && !isNaN(montant) && typeof qte==="number" && !isNaN(qte)) {
      total.value=montant*qte;
    }else{
      total.value="";
    }

    calculateBalance();

  }

  function calculateBalance() {
    var montantPay= parseInt(document.getElementById("montantPay").value);
    var total= parseInt(document.getElementById("total").value);
    var restant=document.getElementById("restant");
    var total_ttc = parseInt(document.getElementById("total_ttc").value);
    var tva = document.getElementById("tva_id").value;

    if (typeof montantPay==="number" && !isNaN(montantPay) && typeof total==="number" && !isNaN(total)) {
      if(tva ==""){
        restant.value=total-montantPay;
      }else{
        restant.value=total_ttc-montantPay;
      }
    }else{
      restant.value="";
    }

    reduction();

  }

  
  function reduction() {
    var total= parseInt(document.getElementById("total").value);
    var reduction= parseInt(document.getElementById("reduction").value);
    var tva_id= parseInt(document.getElementById("tva_id").value);
    var total_ttc=parseInt(document.getElementById("total_ttc").value);
    var restant=parseInt(document.getElementById("restant").value);
    var msg=document.getElementById("success");
    if (!isNaN(tva_id)) {
      if (typeof total_ttc==="number" && !isNaN(total_ttc) && typeof reduction==="number" && !isNaN(reduction)) {
        document.getElementById("total_ttc").value=(total_ttc-reduction);
        document.getElementById("total_ttc").style.border="2px solid green";
         msg.innerHTML="La réduction de " +reduction+ " F est appliquée!";
         document.getElementById("red").value=reduction;
         document.getElementById("restant").value=(restant-reduction);
      }
    } else {
      if (typeof total==="number" && !isNaN(total) && typeof reduction==="number" && !isNaN(reduction)) {
        document.getElementById("total").value=total-reduction;
        document.getElementById("total").style.border="2px solid green";
        msg.innerHTML="La réduction de " +reduction+ " F est appliquée!";
        document.getElementById("red").value=reduction;
        document.getElementById("restant").value=(restant-reduction);
      }
    }
  }
  </script>
@endsection
