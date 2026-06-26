@extends("layout.main")
@section("main")

  <div class="row">
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Modification d'une Boutique</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route("boutique.update", [$datas->id]) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method("PUT")

          <div class="card-body">
            <div class="row">
              <div class="col-md-4">

                <div class="form-group">
                  <label for="gerant_boutique">Gerant_boutique</label>
                  <input type="gerant_boutique" name="gerant_boutique"
                    class="form-control @error('gerant_boutique') is-invalid @enderror" id="exampleInputEmail1"
                    value="{{$datas->gerant_boutique}}" placeholder="">
                  @error('gerant_boutique')
                    <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="nom_boutique">Nom_boutique</label>
                  <input type="nom_boutique" name="nom_boutique" value="{{$datas->nom_boutique}}"
                    class="form-control @error('nom_boutique') is-invalid @enderror" id="exampleInputEmail1"
                    placeholder="">
                  @error("nom_boutique")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="exampleInputPassword1">Adresse</label>
                  <input type="adresse" name="adresse" value="{{$datas->adresse}}"
                    class="form-control @error('adresse') is-invalid @enderror" id="exampleInputPassword1" placeholder="">
                  @error("adresse")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="exampleInputPassword1">Contact</label>
                  <input type="contact" name="contact" value="{{$datas->contact}}"
                    class="form-control @error('contact') is-invalid @enderror" id="exampleInputPassword1" placeholder="">
                  @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="exampleInputPassword1">Logo</label>
                  <input type="file" name="logo" value="{{$datas->logo}}"
                    class="form-control @error('logo') is-invalid @enderror" id="exampleInputPassword1"
                    placeholder="Logo...">
                  @error('logo')
                    <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="exampleInputPassword1">Contact_Gerant</label>
                  <input type="contact_gerant" name="contact_gerant"
                    class="form-control @error('contact_gerant') is-invalid @enderror" id="exampleInputPassword1"
                    value="{{$datas->contact_gerant}}" placeholder="contact_gerant">
                  @error('contact_gerant')
                    <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="exampleInputPassword1">Entreprise</label>
                  <select name="id_setting" id="id_setting"
                    class="form-control @error('id_setting') is-invalid @enderror">
                    <option value="">Choisir entreprise</option>
                    @foreach ($settings as $item)
                      <option value="{{ $item->id }}" {{($item->id == $datas->id_setting) ? "selected" : ""}}>
                        {{ $item->app_name }} - {{$item->contact}}
                      </option>
                    @endforeach
                  </select>
                  @error('id_setting')
                    <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
          </div>

      </div>
      <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-warning" href="{{ route('boutique.index') }}"> Annuler</a>
      </div>
      </form>
    </div>
  </div>
  </div>
@endsection