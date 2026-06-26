@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'une Operation </h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("mobilemoney.update", [$data->id]) }}" method="post">
      @csrf
      @method("PUT")
<div class="card-body">
          <div class="row">
            <div class="col">
          <div class="form-group">
            <label for="service">Service</label>
            <select name="service" class="form-control @error('service') is-invalid @enderror" id="">
              <option value=""></option>
              <option value="Orange Money" {{("Orange Money"==$data->service) ? "selected": ""}}>Orange Money</option>
              <option value="Mobile Cash" {{("Mobile Cash"==$data->service) ? "selected": ""}}>Mobile Cash</option>
              <option value="SAMA Money" {{("SAMA Money"==$data->service) ? "selected": ""}}>SAMA Money</option>
              <option value="Western Union" {{("Western Union"==$data->service) ? "selected": ""}}>Western Union</option>
              <option value="Money Gram" {{(old('service')==$data->service) ? "selected": ""}}>Money Gram</option>
              <option value="Wari" {{("Wari"==$data->service) ? "selected": ""}}>Wari</option>
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
              <option value="Depot" {{("Depot"==$data->types) ? "selected": ""}}>Depot</option>
              <option value="Retrait" {{("Retrait"==$data->types) ? "selected": ""}}>Retrait</option>
              <option value="Envoie" {{("Envoie"==$data->types) ? "selected": ""}}>Envoie</option>
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
                <input type="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{$data->contact}}" id="exampleInputPassword1" placeholder="">
                @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
          </div>
            <div class="col">
                <div class="form-group">
                  <label for="exampleInputPassword1">Montant</label>
                  <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" value="{{$data->montant}}" id="montant" placeholder="">
                  @error("montant")
                      <span class="text-danger"> {{$message}}</span>
                  @enderror
              </div>
          </div>
        </div>
        <div class="form-group">
              <label for="exampleInputPassword1">Détail</label>
              <textarea name="descs" id=""  class="form-control @error('descs') is-invalid @enderror" cols="2" rows="4">{{$data->descs}}</textarea>
              @error("descs")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        <!-- /.card-body -->


        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Modifier</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("mobilemoney.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
