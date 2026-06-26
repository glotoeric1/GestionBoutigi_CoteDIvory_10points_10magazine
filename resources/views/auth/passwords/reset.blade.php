@extends('layout.main_auth')

@section('main')

<div class="card card-outline card-primary" style="border-top: 5px solid #075e98;">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Saisissez votre nouveau mot de passe pour continuer</p>
            <form method="POST" action="{{ route('updatepass.confirm') }}">
                @csrf
                @method("put")
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                        </div>
                    </div>

                    <div class="social-auth-links text-center mt-2 mb-3 fw-bold">
                        <button class="btn btn-block btn-outline-primary rounded-pill" type="submit">Réinitialiser mot de passe</button>
                      </div>
                </form>
                <!-- /.social-auth-links -->
        
                <p class="mb-1">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('login') }}">
                           Se Connecter
                        </a>
                    @endif
                    </p>
                </div>
                <!-- /.card-body -->
                </div>
        <!-- /.card -->

@endsection
