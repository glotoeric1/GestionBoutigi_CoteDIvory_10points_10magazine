@extends("layout.main")
@section("main")
  @php
    $admin = "Admin";
    $vendeur = "Vendeur";
    $gestionaire = "Gestionaire";
    $controlleur = "Controlleur";
    @endphp
  @if (auth()->user()->roles == $vendeur || auth()->user()->roles == $gestionaire)
    <script>window.location = "{{route('vente.create')}}";</script>
  @endif

  <div class="card">
    <div class="card-header">
    <h3 class="card-title">Liste des comptes</h3>
    <a class="btn btn-outline-primary float-right rounded-pill" href="{{ route("users.create") }}"> <i
      class="fas fa-plus"></i> Ajouter</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <a href="{{route("type.index")}}" class="btn btn-outline-success mb-2">Total : {{$total}}</a>
    <table id="example1" class="table table-bordered table-striped table-hover">
      <thead>
      <tr>
        <th>Prénom et Nom</th>
        @if(auth()->user()->roles === "Super Admin")
      <th>Boutique</th>
      @endif
        <th>E-mail</th>
        <th>Contact</th>
        <th>Rôle d'utilisateur</th>
        <th width="120">Action</th>

      </tr>
      </thead>
      <tbody>
      @if(count($datas) > 0)
      @foreach($datas as $data)
      <tr>
        <td>{{ $data->name }}</td>
        @if(auth()->user()->roles === "Super Admin")
      <td>
      {{ optional($data->getBoutique($data->id_boutigue))->nom_boutique ?? 'Non' }}
      </td>
      @endif

        <td>{{ $data->email }}</td>
        <td>{{ $data->contact }}</td>
        <td>{{ $data->roles }}</td>

        <td>

        <!-- activer -->
        <a data-toggle="modal" data-target="#activer{{$data->id}}" href="#">
        <i
        class="@if($data->statut == "1") fas fa-eye px-1  text-success @else fas fa-eye-slash px-1  text-danger  @endif"></i>
        </a>

        <!-- /.modal -->

        <div class="modal fade" id="activer{{$data->id}}">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header modal-head">
        <h5 class="modal-title ">
        @if ($data->statut == "1")
        Desactiver un compte
        @else
        Activer un compte
        @endif
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{route('users.update', [$data->id])}}" method="post">
        @csrf
        @method("PUT")

        <div class="modal-body">
        <h5>
        <input type="hidden" name="types" value="ADMIN" id="">
        @if ($data->statut == "1")
        Voulez vous desactiver le compte de {{$data->name}}
        <input type="hidden" name="statut" value="0" id="">
        @else
        Voulez vous activer le compte de {{$data->name}}
        <input type="hidden" name="statut" value="1" id="">
        @endif
        </h5>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default rounded-pill px-4"
        data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-outline-primary rounded-pill px-4">Confirmer</button>
        </div>
        </form>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- activer -->
        <a data-toggle="modal" data-target="#pass{{$data->id}}" href="#">
        <i class="fas fa-plus px-1 text-success"></i>
        </a>

        <!-- /.modal -->

        <div class="modal fade" id="pass{{$data->id}}">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header modal-head">
        <h4 class="modal-title ">Mot de passe - {{$data->name}} </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <p>
        Code secret : {{$data->secret}}
        </p>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
        </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <a href="{{ route("users.edit", [$data->id]) }}">
        <i class="fa fa-edit"></i>
        </a>

        @if(auth()->user()->roles == "Admin")
      <!-- activer -->
      <a data-toggle="modal" data-target="#del{{$data->id}}" href="#">
        <i class="fas fa-trash px-1 text-danger"></i>
      </a>

      <!-- /.modal -->

      <div class="modal fade" id="del{{$data->id}}">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header modal-head">
        <h4 class="modal-title ">Supprission </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{ route("users.destroy", [$data->id]) }}" method="post">
        @csrf
        @method("DELETE")
        <div class="modal-body">
        <p>
        Voulez vous supprimer {{$data->name}} ?
        </p>
        </div>
        <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-danger rounded-pill">Confirmer</button>
        </div>
        </form>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      @endif

        </td>
      </tr>
      @endforeach

    @endif

      </tbody>
    </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
@endsection