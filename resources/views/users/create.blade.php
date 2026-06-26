@extends("layout.main")
@section("main")
  @if (auth()->user()->roles != "Admin" && auth()->user()->roles != 'Super Admin' && auth()->user()->roles != "Controlleur")
    <script>window.location = "{{route('vente.create')}}";</script>
  @endif
  <div class="row">
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Ajout d'un Compte</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route("users.store") }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Prénom et Nom</label>
                  <input type="name" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                    placeholder="">
                  @error("name")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="contact">Contact</label>
                  <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror"
                    id="contact" placeholder="">
                  @error("contact")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    placeholder="">
                  @error("email")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="categorie">Rôle d'utilisateur</label>
                  <select name="roles" id="" class="form-control @error('roles') is-invalid @enderror">
                    <option value="">...</option>
                    <option value="Vendeur">Vendeur</option>
                    <option value="Gestionaire">Gestionaire</option>
                    <option value="Controlleur">Controlleur</option>
                    @if(auth()->user()->roles == "Admin" || auth()->user()->roles == 'Super Admin')
                      <option value="Admin">Admin</option>
                    @endif
                    @if(auth()->user()->roles == 'Super Admin')
                      <option value="Super Admin">Super Admin</option>
                    @endif
                  </select>
                  @error("roles")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>

              @if(auth()->user()->id === 1)
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="id_setting">Entreprise</label>
                    <select name="id_setting" id="id_setting"
                      class="form-control @error('id_setting') is-invalid @enderror">
                      <option value="">...</option>
                      @if (count($settings) > 0)
                        @foreach ($settings as $s)
                          <option value="{{$s->id}}">{{$s->app_name}}</option>
                        @endforeach
                      @endif
                    </select>
                    @error("id_setting")
                      <span class="text-danger"> {{$message}}</span>
                    @enderror
                  </div>
                </div>
              @else
                <input type="hidden" name="id_setting" id="id_setting" value="{{ auth()->user()->id_setting }}">
              @endif

              @if(auth()->user()->roles == "Super Admin")
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="id_boutique">Boutique</label>
                    <select name="id_boutique" id="id_boutique"
                      class="form-control @error('id_boutique') is-invalid @enderror">
                      <option value="">...</option>
                      @if (count($boutiques) > 0)
                        @foreach ($boutiques as $bou)
                          <option value="{{$bou->id}}">{{$bou->nom_boutique}} - {{$bou->contact}} </option>
                        @endforeach
                      @endif
                    </select>
                    @error("id_boutique")
                      <span class="text-danger"> {{$message}}</span>
                    @enderror
                  </div>
                </div>
              @else
                <input type="hidden" name="id_boutique" id="id_boutique" value="{{ auth()->user()->id_boutigue }}">
              @endif


            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
            <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("users.index") }}"> Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection