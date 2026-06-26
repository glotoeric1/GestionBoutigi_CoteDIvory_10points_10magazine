@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'un Produit</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("dette.update", [$datas->id]) }}" method="post">
      @csrf
      @method("PUT")
        <div class="card-body">


              <div class="form-group">
                <label for="nom_client">Nom_Client</label>
                <input type="nom_client" name="nom_client" value="{{$datas->nom_client}}" class="form-control @error('nom_client') is-invalid @enderror" id="nom_client" placeholder="">
                @error("nom_client")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="id_fournisseur">Adresse</label>
                <input type="adresse_client" name="adresse_client" value="{{$datas->adresse_client}}" class="form-control @error('adress_client') is-invalid @enderror" id="adresse_client" placeholder="">
                @error("adresse_client")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
              <div class="form-group">
                <label for="contact_client">Contact</label>
                <input type="contact_client" name="contact_client" value="{{$datas->contact_client}}" class="form-control @error('contact_client') is-invalid @enderror" id="contact_client" placeholder="">
                @error("contact_client")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>

              <div class="form-group">
                <label for="total">Total</label>
                <input type="total" name="total" value="{{$datas->total}}" class="form-control @error('total') is-invalid @enderror" id="total" placeholder="">
                @error("total")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="avance">Montant_Avance</label>
                <input type="avance" name="avance" class="form-control @error('avance') is-invalid @enderror" value="{{$datas->avance}}" id="avance" placeholder="">
                @error("avance")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>


            <div class="form-group">
                <label for="benefice">Restant</label>
                <input type="restant" name="restant"  value="{{$datas->restant}}"class="form-control @error('restant') is-invalid @enderror" id="restant" placeholder="Contact">
                @error("restant")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>


            <div class="form-group">
                <label for="description">Desription</label>
                <input type="textarea" name="description" class="form-control @error('description') is-invalid @enderror" value="{{$datas->description}}" id="description" placeholder="" >
                @error("description")
                    <span class="text-danger"> {{$message}}</span>
                @enderror
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
