@extends("layout.main")
@section("main")
  @if (auth()->user()->roles != "Admin" && auth()->user()->roles != 'Super Admin')
    <script>window.location = "{{route('vente.create')}}";</script>
  @endif
  <div class="row">
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Modification d'un Compte</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route("users.update", [$datas->id]) }}" method="post">
          @csrf
          @method("PUT")
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Prénom et Nom</label>
                  <input type="name" name="name" value="{{$datas->name}}"
                    class="form-control @error('name') is-invalid @enderror" id="name" placeholder="">
                  @error("name")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="contact">Contact</label>
                  <input type="text" name="contact" value="{{$datas->contact}}"
                    class="form-control @error('contact') is-invalid @enderror" id="contact" placeholder="">
                  @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email" value="{{$datas->email}}"
                    class="form-control @error('email') is-invalid @enderror" id="email" placeholder="">
                  @error("email")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="categorie">Rôle d'utilisateur</label>
                  <select name="roles" id="" class="form-control @error('roles') is-invalid @enderror">
                    <option value="">...</option>
                    <option value="Vendeur" @if($datas->roles == "Vendeur") selected @endif>Vendeur</option>
                    <option value="Gestionaire" @if($datas->roles == "Gestionaire") selected @endif>Gestionaire</option>
                    <option value="Controlleur" @if($datas->roles == "Controlleur") selected @endif>Controlleur</option>
                    @if(auth()->user()->roles == "Admin" || auth()->user()->roles == 'Super Admin')
                      <option value="Admin" @if($datas->roles == "Admin") selected @endif>Admin</option>
                    @endif
                  </select>
                  @error("roles")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              @if(auth()->user()->roles == "Super Admin")
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="Boutique">Boutique</label>
                    <select name="id_boutique" id="id_boutique"
                      class="form-control @error('id_boutique') is-invalid @enderror">
                      <option value="">...</option>
                      @if (count($boutiques) > 0)
                        @foreach ($boutiques as $bou)
                          <option value="{{$bou->id}}" {{($bou->id == $datas->id_boutigue) ? 'selected' : ''}}>
                            {{$bou->nom_boutique}} -
                            {{$bou->contact}}
                          </option>
                        @endforeach
                      @endif
                    </select>
                    @error("id_boutique")
                      <span class="text-danger"> {{$message}}</span>
                    @enderror
                  </div>
                </div>
              @endif
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Modifier</button>
            <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("users.index") }}"> Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection