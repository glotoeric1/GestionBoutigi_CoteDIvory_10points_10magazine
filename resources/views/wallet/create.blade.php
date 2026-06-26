@extends("layout.main")

@section("main")

    <div class="row">
        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Nouvelle opération - {{ $client->nom }}
                    </h3>
                </div>

                <form action="{{ route('wallet.store', $client->id) }}" method="post">
                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label>Type d'opération</label>
                            <select name="type" class="form-control" required>
                                <option value="depot">Dépôt</option>
                                <option value="retrait">Retrait</option>
                                <option value="remboursement">Remboursement</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Montant</label>
                            <input type="number" name="montant" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>

                    </div>

                    <div class="card-footer">
                        <button class="btn btn-outline-primary rounded-pill px-4">
                            Valider
                        </button>

                        <a href="{{ route('wallet.index', $client->id) }}"
                            class="btn btn-outline-warning rounded-pill px-4">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection