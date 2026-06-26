@extends('layout.main_auth')
  @section('main')
  <!-- /.login-logo -->
  <!-- Check if 'check' is passed -->
      @if(isset($check))
        <h4>{{ $check->warning_message }}</h4>
        <p>Expiration Date: {{ $check->date_fin->format('d/m/Y') }}</p>
    @else 
  <div class="card card-outline card-primary" style="border-top: 5px solid #075e98;">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Connectez-vous pour démarrer le travail.</p>
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="input-group mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
              @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mot de passe">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                  Se souvenir de moi
                </label>
              </div>
            </div>
          </div>
      
        <div class="social-auth-links text-center mb-3">
          <button type="submit" class="btn btn-block btn-outline-primary rounded-pill">
              Se Connecter
          </button>
        </div>
      </form>
        <!-- /.social-auth-links -->
      
      @if (Route::has('password.request'))
        <p class="mb-1">
          <a href="{{ route('password.request') }}">Mot de passe oublié</a>
        </p>
      @endif
      </div>
      <!-- /.login-card-body -->
    </div>
      @endif

  @endsection
