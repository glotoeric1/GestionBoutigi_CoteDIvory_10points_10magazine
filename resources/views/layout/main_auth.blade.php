
@php
$loginColor="#4B9FD8";
$loginColor="#4B9FD8";
$navbarColor="#E0E3F5";
$entreprise="Application Boutiqi";
$logo="backend/images/default_logo.jpeg";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$entreprise}} | authentification</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('backend/dist/img/favicon.ico')}}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
      @php
    echo "
      <style>
        .sidebar-dark-primary{
        background: {$loginColor};
        background: linear-gradient(355deg, {$loginColor} 2%, {$navbarColor} 100%);
        }
    </style>" ;
    @endphp
</head>
<body class="hold-transition login-page sidebar-dark-primary">
  @if (session('succes'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"
            aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Succes</h5>
        {{ session('succes') }}
    </div>
@elseif(session('message'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"
            aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Application Bloquez</h5>
        {{ session('message') }}
    </div>
@elseif(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"
            aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Erreur</h5>
        {{ session('error') }}
    </div>
@endif

<div class="login-box">
  <div class="login-logo">
    <a href="{{url("/")}}">
        <img src="{{ asset($logo) }}" alt="{{$entreprise}}" class="rounded" width="120" height="120">
    </a>

  </div>
    @yield("main")
  <br>
  <hr>
  <p class="text-center rounded py-1" style="background-color: #f3f3f3; font-weight: bold;"> 
    <span style="text-decoration: underline">Besoin d'aide </span> <br> Tél : (+223) 83859008 / 73231645<br>
    Email : skillcodiing@gmail.com<br>
    Produit de : <a href="https://skillcodiing.com" target="_blank">
    Skill Codiing</a>
</p>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
