@extends("layout.main")
@section("main")
<hr>
<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'un  Produit</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("produit.store") }}" method="post" id="form1">
      @csrf
        <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4"></div>
          <div class="col-md-4">
            <h4 id="num_charge" class="px-3"> Numéro du commande : <span id="charge"></span> </h4>
          </div>
          <div class="col-md-4"></div>
        </div>
        <div class="row g-3">
        <div class="col-md-12">
          <div class="form-group">
            <label for="exampleInputPassword1">Nom de Produit</label>
            <input type="hidden" name="nom_produit" value="{{old('nom_produit')}}" id="nom_produit">
            <input type="hidden" name="numero_comm" value="{{old('numero_comm')}}" id="numero_comm">
            <select name="id_produit" class="form-control @error('id_produit') is-invalid @enderror" id="id_produit">
                <option value=""></option>
                @if (count($pros)>0)
                  @foreach ($pros as $pro)
                    <option value="{{$pro->id}}">{{$pro->product}}</option>
                  @endforeach
                @endif
            </select>
            @error("id_produit")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
          <div class="form-group">
            <label for="id_categorie">Catégorie</label>
            <select name="id_categorie" id="id_categorie" class="form-control @error('id_categorie') is-invalid @enderror">
              <option value="">...</option>
              @if (count($cats)>0)
                @foreach ($cats as $cat)
                <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
                @endforeach
              @endif
            </select>
            @error("id_categorie")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label for="id_type">Sous Categorie</label>
            <select name="id_type" id="id_type" class="form-control @error('id_type') is-invalid @enderror">
              <option value="">...</option>
              
            </select>
            @error("id_type")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>

      </div>
   
      <div class="col-md-4">
        <div class="form-group">
            <label for="id_fournisseur">Date d'expiration (optionnel)</label>
            <input type="date" value="" name="date_expiration" class="form-control @error('date_expiration') is-invalid @enderror" id="date_expiration">
            @error("date_expiration")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
          <div class="form-group">
            <label for="quantite">Quantite</label>
            <input type="text" name="quantite" onblur="CallAllMeth()"  class="form-control @error('quantite') is-invalid @enderror" id="qte_achat" placeholder="">
            @error("quantite")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="prix_achat">Prix Achat</label>
            <input type="text" name="prix_achat" onblur="CallAllMeth()" class="form-control @error('prix_achat') is-invalid @enderror" id="prix_achat" placeholder="">
            @error("prix_achat")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>

        <div class="col-md-4">
        <div class="form-group">
          <label for="total">Total</label>
          <input type="text" name="Total_achat" class="form-control @error('Total_achat') is-invalid @enderror" id="total_achat" placeholder="" readonly>
          @error("Total_achat")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>

    </div> 
  </div>
<h3 class="text-center">Prix en gros</h3> <hr>
  <div class="row g-3">
    <div class="col-md-4">
        <div class="form-group">
            <label for="prix_vente">Prix de vente (Prix en gros)</label>
            <input type="text" name="prix_vente_en_gros" onblur="CallAllMeth()" class="form-control @error('prix_vente_en_gros') is-invalid @enderror" id="prix_vente_en_gros" placeholder="">
            @error("prix_vente_en_gros")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="total_apre_vente">Total Apres Vente (En gros)</label>
          <input type="text" name="Total_en_gros" class="form-control @error('Total_en_gros') is-invalid @enderror" id="Total_en_gros" placeholder="Total apre vente" readonly>
          @error("Total_en_gros")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
         
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="benefice">Bénéfice sans charge(Gros)</label>
        <input type="text" name="Total_benefice_en_gros" class="form-control @error('Total_benefice_en_gros') is-invalid @enderror" id="Total_benefice_en_gros" placeholder="benefice" readonly>
        @error("Total_benefice_en_gros")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
       
  </div>
    </div>
    <h3 class="text-center">Prix unitaire (en detaile)</h3> <hr>
    <div class="row g-3">
      <div class="col-md-3">
          <div class="form-group">
              <label for="prix_vente">Prix unitaire de vente (Prix en detaile)</label>
              <input type="text" name="prix_vente_unitaire" onblur="CallAllMeth()" class="form-control @error('prix_vente_unitaire') is-invalid @enderror" id="prix_vente_unitaire" placeholder="">
              @error("prix_vente_unitaire")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
              <label for="prix_vente">Qte par carton</label>
              <input type="text" name="qte_par_carton" onblur="CallAllMeth()" class="form-control @error('qte_par_carton') is-invalid @enderror" id="qte_par_carton" placeholder="Qte par carton">
              @error("qte_par_carton")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
              <label for="prix_vente">Qte total (unitaire)</label>
              <input type="text" name="qte_total_en_detail" class="form-control @error('qte_total_en_detail') is-invalid @enderror" id="qte_total_en_detail" placeholder="Qte total de vente" readonly>
              @error("qte_total_en_detail")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
  
        <div class="col-md-3">
          <div class="form-group">
            <label for="Total_en_detail">Total Apres Vente (Unitaire)</label>
            <input type="text" name="Total_en_detail" class="form-control @error('Total_en_detail') is-invalid @enderror" id="Total_en_detail" placeholder="Total apre vente" readonly>
            @error("Total_en_detail")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
           
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="benefice">Bénéfice sans charge (Détail)</label>
          <input type="text" name="Total_benefice_en_detail" class="form-control @error('Total_benefice_en_detail') is-invalid @enderror" id="Total_benefice_en_detail" placeholder="benefice" readonly>
          @error("Total_benefice_en_detail")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
         
    </div>
      </div>
        <div class="row g-3">
          <div class="col-md-4">
          <div class="form-group">
            <label for="code_barre">Utiliser code barre</label>
            <select name="options_barcode" id="options_barcode" class="form-control @error('options_barcode') is-invalid @enderror" required>
              <option value="">...</option>
              <option value="NON">NON</option>
              <option value="OUI">OUI</option>
            </select>
            @error("options_barcode")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-8 d-none" id="code">
        <div class="form-group">
          <label for="code_barre">Code barre</label>
          <input type="text" name="code_barre" class="form-control @error('code_barre') is-invalid @enderror" id="code_barre" placeholder="" >
          @error("code_barre")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    </div>
  </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary m-1 rounded-pill mx-2 px-4">Enregistrer</button>
          <button type="reset" class="btn btn-outline-info m-1 rounded-pill mx-2 px-4">Annuler</button>
          <a class="btn btn-outline-warning m-1 rounded-pill mx-2 px-4" href="{{ route("produit.index") }}"> Retrouner</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section("scripts")
<script>
//Get all students information
jQuery('select[name="id_categorie"]').on('change',function(){
    var studentId = jQuery(this).val();
    if(studentId)
    {
      jQuery.ajax({
          url : '/getcat/' +studentId,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            if(data){
                $('#id_type').empty();
                $('#id_type').append('<option hidden>...</option>'); 
                $.each(data, function(key, course){
                    $('select[name="id_type"]').append('<option value="'+ course.id +'">' + course.nom_type+ '</option>');
                });
            }else{
                $('#id_type').empty();
            }

          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
      $('#id_type').empty();
    }
});

jQuery('select[name="options_barcode"]').on('change',function(){
    var options = jQuery(this).val();
    //var codebar=$('#code_barre').attr("id");
    //alert(codebar)
    if(options=="OUI")
    {
      $('#code').removeClass("d-none");
      $('#generate').removeClass("d-none");
      $('#produit').addClass("col-md-7");
      $('#produit').removeClass("col-md-10");
    }
    else
    {
      $('#code').addClass("d-none");
      $('#generate').addClass("d-none");
      $('#produit').removeClass("col-md-7");
      $('#produit').addClass("col-md-10");
    }
});

jQuery('select[name="id_produit"]').on('change',function(){
    var studentId = jQuery(this).val();
    if(studentId)
    {
      jQuery.ajax({
          url : '/getPrix/' +studentId,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            if(data){
                  $('#qte_achat').val("");
                  $('#prix_achat').val("");
                  $('#nom_produit').val("");
                $.each(data, function(key, course){
                    //console.log(course);
                    $('#qte_achat').val(course.qte_valider);
                    $('#prix_achat').val(course.prix);
                    $('#nom_produit').val(course.libelle);
                    $('#numero_comm').val(course.numero_achat);
                    $('#charge').html(course.numero_achat);
                    
                    //BORDER COLOR
                    $('#prix_achat').css('border', '3px solid #198754');
                    $('#num_charge').css('border', '3px solid #198754');
                    $('#prix_achat').css('border', '3px solid #198754');
                    $('#qte_achat').css('border', '3px solid #198754');
                    //$('#prix_achat').style.borderColor = "green";
                });
            }else{
                $('#qte_achat').val("");
                $('#prix_achat').val("");
                $('#nom_produit').val("");
            }

          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
      $('#qte_stock').val("");
      $('#prix_achat').val("");
      $('#nom_produit').val("");
    }
});
  </script>
@endsection
