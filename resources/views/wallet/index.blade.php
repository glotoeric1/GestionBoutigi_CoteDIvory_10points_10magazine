@extends("layout.main")

@section("main")

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Historique Portefeuille - {{ $client->nom }}
            </h3>

            <a href="{{ route('client.show', $client->id) }}" class="btn btn-outline-danger float-right rounded-pill">
                <i class="fas fa-backward"></i> Retour
            </a>
        </div>

        <div class="card-body">

            <div class="alert alert-info">
                Solde actuel :
                <strong>{{ number_format($client->wallet_balance, 0, ",", " ") . config("app.cc") }}</strong>
            </div>

            <table id="example1" class="table table-borderedless table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Avant</th>
                        <th>Après</th>
                        <th>Description</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($datas as $data)
                        <tr>
                            <td>{{ $data->created_at }}</td>
                            <td>
                                @if($data->type == 'depot')
                                    <span class="badge badge-success">Dépôt</span>
                                @elseif($data->type == 'retrait')
                                    <span class="badge badge-danger">Retrait</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($data->type) }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($data->montant, 0, ",", " ") . config("app.cc") }}</td>
                            <td>{{ number_format($data->solde_avant, 0, ",", " ") . config("app.cc") }}</td>
                            <td>{{ number_format($data->solde_apres, 0, ",", " ") . config("app.cc") }}</td>
                            <td>{{ $data->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection