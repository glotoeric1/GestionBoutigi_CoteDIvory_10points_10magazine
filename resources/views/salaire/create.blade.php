@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'un Salaire</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("salaires.store") }}" method="post">
      @csrf
        <div class="card-body">

          <div class="row g-3">
            <div class="col-md-4">

              <div class="form-group"></div>
          </div>
          <div class="col-md-4">

            <div class="form-group">
              <label for="contact">Employés</label>
              <select name="emp_id" id="emp_id" onblur="calculateBalance()" class="form-control @error('contact') is-invalid @enderror">
                <option value="">...</option>
                @if (count($datas)>0)
                  @foreach ($datas as $data)
                    <option value="{{$data->id}}">{{$data->nom}}</option>
                  @endforeach
                @endif
              </select>
              @error("emp_id")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-4"></div>
        </div>
        <div class="row g-3">
          <div class="col-md-4">

            <div class="form-group">
              <label for="salaire">Salaire </label>
              <input type="text" name="salaire" onblur="calculateBalance()" class="form-control @error('salaire') is-invalid @enderror" id="salaire" placeholder="" readonly>
              @error("salaire")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-4">

          <div class="form-group">
            <label for="montantRecu">Montant Réçu</label>
            <input type="text" name="montantRecu" onblur="calculateBalance()" class="form-control @error('montantRecu') is-invalid @enderror" id="montantRecu" placeholder="" >
            @error("montantRecu")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="montantRestant">Montant Restant</label>
          <input type="text" name="montantRestant" class="form-control @error('montantRestant') is-invalid @enderror" id="montantRestant" placeholder="" readonly >
          @error("montantRestant")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>
      <div class="row g-3">
        <div class="col-md-4">

          <div class="form-group">
            <label for="years">Année</label>
            @php
              $years = range(date('Y'), 2022);
            @endphp
            <select name="years" class="form-control @error('years') is-invalid @enderror" id="years" >
              <option value="">...</option>
              @foreach ($years as $year)
                <option value="{{$year}}">{{$year}}</option>
              @endforeach
          </select>
            @error("years")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="salaire">Mois</label>
          <select name="mois" class="form-control @error('mois') is-invalid @enderror" id="mois" >
            <option value="">...</option>
              <option value="Janvier">  Janvier</option>
              <option value="Fevrier"> Fevrier</option>
              <option value="Mars">  Mars</option>
              <option value="Avril"> Avril</option>
              <option value="Mai">  Mai</option>
              <option value="Juin"> Juin</option>
              <option value="Jullet">  Jullet</option>
              <option value="Août"> Août</option>
              <option value="Septembre">  Septembre</option>
              <option value="October"> October</option>
              <option value="November ">  Novenber </option>
              <option value="Décember"> Décembre</option>
        </select>
          
          @error("mois")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">

      <div class="form-group">
        <label for="bonus">Bonus (Optional)</label>
        <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" id="bonus" placeholder="" >
        @error("bonus")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>

        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <button type="submit" name="btn" value="PRINT" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer & Impr</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("salaires.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section("scripts")
  <script>
//Get all students information
jQuery('select[name="emp_id"]').on('change',function(){
    var emp_id = jQuery(this).val();
    //alert(emp_id)
    if(emp_id)
    {
      jQuery.ajax({
          url : '/getsalaire/' +emp_id,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            jQuery.each(data, function(key,value){
                $("#salaire").val(value.salaire);
              });

          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
      $('#salaire').val("");
    }
});

  function calculateBalance() {
    var salary= parseInt(document.getElementById("salaire").value);
    var amountReceived= parseInt(document.getElementById("montantRecu").value);
    var restant=document.getElementById("montantRestant");

    if (typeof salary==="number" && !isNaN(salary) && typeof amountReceived==="number" && !isNaN(amountReceived)) {
      restant.value=salary-amountReceived;
    }else{
      restant.value="";
    }

  }
  </script>
@endsection
