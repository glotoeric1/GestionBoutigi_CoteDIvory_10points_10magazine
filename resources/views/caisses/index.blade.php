@extends("layout.main")
@section("main")

  @php
    $currency = $currency ?? ' F CFA';
  @endphp

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">La caisse globale</h3>
      <a href="#" class="btn btn-outline-primary rounded-pill float-right mx-2" onclick="showForm()" id="btnOpen">
        <i class="fas fa-search"></i> Recherche Avancée
      </a>
      <a href="#" class="btn btn-outline-danger rounded-pill float-right mx-2 d-none" onclick="closeForm()" id="btnClose">
        <i class="fas fa-minus"></i> Fermer
      </a>
    </div>

    <!-- Search Form -->
    <div id="form" class="mb-5 d-none">
      <form action="{{ route('caisse.Search') }}" method="get" class="form-control">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <div class="form-group">
              <label for="dateDebut" class="form-label">Date début</label>
              <input type="date" name="dateDebut" class="form-control @error('dateDebut') is-invalid @enderror"
                id="dateDebut">
              @error("dateDebut")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="dateFin" class="form-label">Date fin</label>
              <input type="date" name="dateFin" class="form-control @error('dateFin') is-invalid @enderror" id="dateFin">
              @error("dateFin")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
        <div class="gap-2 d-md-flex d-md-block justify-content-center">
          <button type="submit" name="option" value="" class="btn btn-outline-primary mx-2 px-5 rounded-pill">
            Recherche
          </button>
          <button type="submit" name="option" value="PRINT" class="btn btn-outline-primary mx-2 px-5 rounded-pill">
            Recherche & Imprimer
          </button>
          <button type="reset" class="btn btn-outline-warning mx-2 px-5 rounded-pill">
            Annuler
          </button>
        </div>
        <hr>
      </form>
    </div>

    <!-- /.card-header -->
    <div class="card-body mt-3">
      <h2 class="bg-info text-center py-2">{{ $title }}</h2>

      <!-- Row 1: Total dû, Stock Value -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total dû (Ventes)</span>
              <span class="info-box-number">
                {{ number_format($totalDu, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-basket"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Valeur du Stock</span>
              <span class="info-box-number">
                {{ number_format($stockValue, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
              <span class="progress-description">
                Qté totale : {{ $stockQuantity }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 2: Reduction, Net à payer, Total payé -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-tags"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Réduction</span>
              <span class="info-box-number">
                {{ number_format($totalReduction, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calculator"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Net à payer</span>
              <span class="info-box-number">
                {{ number_format($netAPayer, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total payé</span>
              <span class="info-box-number">
                {{ number_format($totalPaye, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 3: Reste à payer, Stock Quantity -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Reste à payer</span>
              <span class="info-box-number">
                {{ number_format($totalRestant - $totalReduction, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-balance-scale"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Écart (payé - net)</span>
              <span class="info-box-number">
                {{ number_format($totalPaye - $netAPayer, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-light elevation-1"><i class="fas fa-cubes text-dark"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Quantité en Stock</span>
              <span class="info-box-number">
                {{ $stockQuantity }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Profit section (unchanged) -->
      <h4 class="text-center mt-4">Bénéfice sur Ventes</h4>
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Bénéfice Vente en Détail</span>
              <span class="info-box-number">
                {{ number_format($beneficeVenteDetail, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chart-bar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Bénéfice Vente en Gros</span>
              <span class="info-box-number">
                {{ number_format($beneficeVenteGros, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Profit -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="info-box bg-light">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Bénéfice Total</span>
              <span class="info-box-number">
                {{ number_format($beneficeVenteDetail + $beneficeVenteGros, 0, ",", " ") }}<small>{{ $currency }}</small>
              </span>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

@endsection

@push('scripts')
  <script>
    function showForm() {
      document.getElementById('form').classList.remove('d-none');
      document.getElementById('btnOpen').classList.add('d-none');
      document.getElementById('btnClose').classList.remove('d-none');
    }
    function closeForm() {
      document.getElementById('form').classList.add('d-none');
      document.getElementById('btnOpen').classList.remove('d-none');
      document.getElementById('btnClose').classList.add('d-none');
    }
  </script>
@endpush