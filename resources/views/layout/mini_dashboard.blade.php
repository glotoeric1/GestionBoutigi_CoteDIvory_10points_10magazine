@guest
  <script>window.location = "{{ route('/') }}";</script>
@endguest

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Point de vente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e7f1 100%);
      color: #212529;
    }

    .wrapper {
      flex: 1;
    }

    .content-header {
      background: linear-gradient(120deg, #007bff, #007bff);
      padding: 1rem 0;
      margin-bottom: 2.5rem;
    }

    .content-header h1 {
      font-weight: 200;
      font-size: 1.8rem;
      color: white;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .content-header hr {
      height: 4px;
      background: rgba(255, 255, 255, 0.5);
      border: none;
      margin: 1.5rem auto;
      border-radius: 2px;
      width: 70px;
    }

    .empty-state {
      background: white;
      border-radius: 16px;
      padding: 3rem 2rem;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      max-width: 600px;
      margin: 2rem auto;
    }

    .empty-state i {
      font-size: 4rem;
      color: #dee2e6;
      margin-bottom: 1.5rem;
    }

    .empty-state h4 {
      font-weight: 600;
      margin-bottom: 1rem;
      color: #212529;
    }

    .stats-container {
      max-width: 1200px;
      margin: 0 auto 3rem;
      padding: 0 1rem;
    }

    .stat-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 10px 20px rgba(149, 157, 165, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      height: 100%;
      border: none;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(67, 97, 238, 0.15);
    }

    .stat-card i {
      font-size: 2.5rem;
      color: #007bff;
      margin-bottom: 1rem;
    }

    .stat-card h3 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .stat-card p {
      color: #6c757d;
      margin-bottom: 0;
      font-size: 0.95rem;
    }

    @media (max-width: 768px) {
      .content-header {
        padding: 2.5rem 0;
      }

      .content-header h1 {
        font-size: 2.2rem;
      }
    }
  </style>
</head>

<body class="hold-transition">

  <!-- Main content wrapper -->
  <div class="wrapper">
    <div class="content-header text-center">
      <div class="container-fluid">
        <h1 class="m-0 fw-bold">Dashboard – Point de vente</h1>
        <h5 class="m-0 fw-bold text-white pt-3">Bonjour : {{ auth()->user()->name }}</h5>
        <hr class="mx-auto">
      </div>
    </div>

    <div class="stats-container">
      <form method="POST" action="{{ route('select.boutique.submit') }}">
        @csrf
        <div class="row g-4 justify-content-center">
          @forelse ($boutiques as $b)
        <div class="col-md-3">
        <div class="stat-card">
          <i class="fas fa-store-alt"></i>
          <h3>{{ $b->nom_boutique }}</h3>
          <p>
          Adresse : {{ $b->adresse }} <br>
          Contact : {{ $b->contact }} <br>
          Gérant : {{ $b->gerant_boutique }}
          </p>
          <hr>
          <button type="submit" name="boutique_id" value="{{ $b->id }}"
          class="btn btn-outline-primary btn-sm rounded-pill px-4">
          Accéder
          </button>
        </div>
        </div>
      @empty
        <div class="col-12">
        <div class="empty-state">
          <i class="fas fa-store-slash"></i>
          <h4>Aucune point de vente enregistrée.</h4>
          <p>Commencez par ajouter votre première point de vente</p>
          @if(auth()->id() === 1)
        <i class="fas fa-plus me-2"></i> 
        <a href="{{ route("settings.index") }}" class="btn text-primary">Ajouter un point de vente</a>
      @endif
        </div>
        </div>
      @endforelse
        </div>
      </form>
      <hr>
      <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-outline-danger rounded-pill">
            <i class="fas fa-sign-out-alt me-2"></i> Se déconnecter
          </button>
        </form>
      </div>

    </div>
  </div>

  <!-- Fixed Footer OUTSIDE .wrapper -->
  <footer class="main-footer py-3 border-top" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <a href="{{url("https://www.skillcodiing.com")}}" target="_blank">
            <strong>&copy; Skill Codiing </strong>
          </a>
        </div>
        <div class="col-md-6">
          <div class="float-md-end text-center text-md-end">
            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1">
              <span class="badge bg-primary rounded-pill me-2">v2.0</span>
              <small class="text-muted">Premium Edition</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const cards = document.querySelectorAll('.card');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = "1";
          card.style.transform = "translateY(0)";
        }, 100 * index);
      });
    });
  </script>
</body>

</html>