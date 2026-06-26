
  @section('main')
  <!-- /.login-logo -->
  <div class="card card-outline card-primary" style="border-top: 5px solid #075e98;">
      <div class="card-body login-card-body">
        
        <p class="login-box-msg">Votre d'application est bloquer</p>
        <form method="POST" action="{{ route('app.setting') }}">
          @csrf
          <div class="input-group mb-3">
            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus placeholder="Code d'activation">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
              @error('code')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
        <div class="social-auth-links text-center mb-3">
          <button type="submit" class="btn btn-block btn-outline-primary rounded-pill">
              Activer Application
          </button>
        </div>
      </form>

      </div>
      <!-- /.login-card-body -->
    </div>
  @endsection

