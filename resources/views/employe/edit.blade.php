@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'un Employé</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("employes.update", [$datas->id]) }}" method="post">
      @csrf
      @method("PUT")
      <div class="card-body">

        <div class="row g-3">
          <div class="col-md-4">

            <div class="form-group">
              <label for="nom">Nom </label>
              <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{$datas->nom}}" id="nom" placeholder="" >
              @error("nom")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-4">

          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{$datas->contact}}"  id="contact" placeholder="" >
            @error("contact")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="adresse">Adresse</label>
          <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{$datas->adresse}}"  id="adresse" placeholder="" >
          @error("adresse")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>
      <div class="row g-3">
        <div class="col-md-4">

          <div class="form-group">
            <label for="post">Post </label>
            <input type="text" name="post" class="form-control @error('post') is-invalid @enderror" value="{{$datas->post}}"  id="post" placeholder="" >
            @error("post")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="salaire">Salaire</label>
          <input type="text" name="salaire" class="form-control @error('salaire') is-invalid @enderror" value="{{$datas->salaire}}"  id="salaire" placeholder="" >
          @error("salaire")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">

      <div class="form-group">
        <label for="dateStart">Date Début</label>
        <input type="date" name="dateStart" class="form-control @error('dateStart') is-invalid @enderror" value="{{$datas->dateStart}}"  id="dateStart" placeholder="" >
        @error("dateStart")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>
    <div class="row g-3">
      <div class="col-md-4">

        <div class="form-group">
          <label for="emergency_name">Nom à Joindre </label>
          <input type="text" name="emergency_name" class="form-control @error('emergency_name') is-invalid @enderror" value="{{$datas->emergency_name}}"  id="emergency_name" placeholder="Saisissez nom à contacter" >
          @error("emergency_name")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">

      <div class="form-group">
        <label for="salaire">Contact à Joindre </label>
        <input type="text" name="contact_joint" class="form-control @error('contact_joint') is-invalid @enderror" value="{{$datas->contact_joint}}"  id="contact_joint" placeholder="Saisissez numéro de la personne">
        @error("contact_joint")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
  <div class="col-md-4">

    <div class="form-group">
      <label for="relationship">Lien de la Relation</label>
      <input type="text" name="relationship" class="form-control @error('relationship') is-invalid @enderror" value="{{$datas->relationship}}" id="relationship" placeholder="Ex: Frère, Père, Mère, Femme, Marie etc">
      @error("relationship")
          <span class="text-danger"> {{$message}}</span>
      @enderror
  </div>
</div>
  </div>

      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
        <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("employes.index") }}"> Annuler</a>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
