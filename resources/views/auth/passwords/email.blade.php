@extends('layout.main_auth')

@section('main')
<!-- /.login-logo -->
<div class="card card-outline card-primary" style="border-top: 5px solid #075e98;">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Vous avez oublié votre mot de passe? Ici, vous pouvez facilement récupérer un nouveau mot de passe.</p>
    
          <form action="{{route("passwords.email")}}" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-outline-primary btn-block rounded-pill">Envoyer</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
    
          <p class="mt-3 mb-1">
            <a href="{{route("login")}}">Se Connecter</a>
          </p>
        </div>
        <!-- /.login-card-body -->
      </div>
</div>
@endsection

