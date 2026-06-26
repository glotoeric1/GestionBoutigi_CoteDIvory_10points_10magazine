@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Ajout d'une Operation </h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("mobilemoney.store") }}" method="post">
      @csrf
        <div class="card-body">
          <div class="row">
            <div class="col">
          <div class="form-group">
            <label for="service">Service</label>
            <select name="service" class="form-control @error('service') is-invalid @enderror" id="">
              <option value="">-----------------Credit téléphone-----------------</option>
              <option value="Credit Orange">Credit Orange</option>
              <option value="Credit Malitel">Credit Malitel</option>
              <option value="Credit Telecel">Credit Telecel</option>
              <option value="">-----------------Transfert Argent-----------------</option>
              <option value="Orange Money">Orange Money</option>
              <option value="Mobile Cash">Mobile Cash</option>
              <option value="SAMA Money">SAMA Money</option>
              <option value="Western Union">Western Union</option>
              <option value="Money Gram">Money Gram</option>
              <option value="Wari">Wari</option>
            </select>
            @error("service")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
         </div>

           <div class="col">
          <div class="form-group">
            <label for="exampleInputPassword1">Type d'operation</label>
           <select name="types" class="form-control @error('types') is-invalid @enderror" id="">
              <option value=""></option>
              <option value="Depot">Depot</option>
              <option value="Retrait">Retrait</option>
              <option value="Envoie">Envoie</option>
            </select>
            @error("types")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
         </div>
        </div>
         <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="exampleInputPassword1">Contact (Telephone)</label>
                <input type="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" id="exampleInputPassword1" placeholder="">
                @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
          </div>
            <div class="col">
                <div class="form-group">
                  <label for="exampleInputPassword1">Montant</label>
                  <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" id="montant" placeholder="">
                  @error("montant")
                      <span class="text-danger"> {{$message}}</span>
                  @enderror
              </div>
          </div>
        </div>
        <div class="form-group">
              <label for="exampleInputPassword1">Détail</label>
              <textarea name="descs" id=""  class="form-control @error('descs') is-invalid @enderror" cols="2" rows="4"></textarea>
              @error("descs")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("mobilemoney.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
