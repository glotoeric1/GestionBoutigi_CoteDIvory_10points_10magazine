@extends("layout.main")
@section("main")

<div class="row">
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Modification d'une Configuration</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route("settings.update", [$data->id]) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method("PUT")
      <div class="card-body">

        <div class="row g-3">
          <div class="col-md-4">

            <div class="form-group">
              <label for="app_name">Nom de entreprise </label>
              <input type="text" name="app_name" value="{{$data->app_name}}" class="form-control @error('app_name') is-invalid @enderror" id="app_name" placeholder="" required >
              @error("app_name")
                  <span class="text-danger"> {{$message}}</span>
              @enderror
          </div>
        </div>
        <div class="col-md-4">

          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" name="contact" value="{{$data->contact}}" class="form-control @error('contact') is-invalid @enderror" id="contact" placeholder="" required>
            @error("contact")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="address">Type de facture </label>
            <select name="address" class="form-control @error('address') is-invalid @enderror" id="address">
              <option value=""></option>
              <option value="A4"{{($data->address=="A4") ? 'selected':''}}>A4</option>
              <option value="A5"{{($data->address=="A5") ? 'selected':''}}>A5</option>
              <option value="A6"{{($data->address=="A6") ? 'selected':''}}>A6</option>
              <option value="A7"{{($data->address=="A7") ? 'selected':''}}>A7</option>
              <option value="A8"{{($data->address=="A8") ? 'selected':''}}>A8</option>
            </select>
            @error("address")
                <span class="text-danger"> {{$message}}</span>
            @enderror
          @error("address")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
      </div>
      <div class="row g-3">
        <div class="col-md-4">

          <div class="form-group">
            <label for="types">types </label>
            <input type="text" name="types" value="{{$data->types}}" class="form-control @error('types') is-invalid @enderror" id="types" placeholder="" required>
            @error("types")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-4">

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title" value="{{$data->title}}" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="" required>
          @error("title")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="footer">Le footer de facteur</label>
        <input type="text" name="footer" value="{{$data->footer}}" class="form-control @error('footer') is-invalid @enderror" id="footer" placeholder="Ex: SkillCodiing vous remercie" >
        @error("footer")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>
     <div class="row g-3">
        <div class="col-md-3">
          <div class="form-group">
            <label for="name_user">Nom de Directeur / Gerant</label>
            <input type="text" name="name_user" value="{{$data->name_user}}" class="form-control @error('name_user') is-invalid @enderror" id="name_user" placeholder="Nom de Gerant"  required>
            @error("name_user")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-3">

        <div class="form-group">
          <label for="contact_user">Contact DG</label>
          <input type="text" name="contact_user" value="{{$data->contact_user}}" class="form-control @error('contact_user') is-invalid @enderror" id="contact_user" placeholder="Saisissez contacter de DG" required>
          @error("contact_user")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="logo">Logo Application</label>
        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="logo" placeholder="">
        @error("logo")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="logo2">Logo Facture</label>
        <input type="file" name="logo2" class="form-control @error('logo2') is-invalid @enderror" id="logo2" placeholder="">
        @error("logo2")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
  <div class="col-md-2">
      <div class="form-group">
        <label for="qte_alert">Alert de stock</label>
        <select name="qte_alert" class="form-control @error('qte_alert') is-invalid @enderror" id="qte_alert">
            <option value=""></option>
            <option value="5" {{($data->qte_alert=="5") ? "selected" : ""}}>5</option>
            <option value="10" {{($data->qte_alert=="10") ? "selected" : ""}}>10</option>
            <option value="20" {{($data->qte_alert=="20") ? "selected" : ""}}>20</option>
            <option value="30" {{($data->qte_alert=="30") ? "selected" : ""}}>30</option>
            <option value="50" {{($data->qte_alert=="50") ? "selected" : ""}}>50</option>
            <option value="80" {{($data->qte_alert=="80") ? "selected" : ""}}>80</option>
            <option value="100" {{($data->qte_alert=="100") ? "selected" : ""}}>100</option>
            <option value="150" {{($data->qte_alert=="150") ? "selected" : ""}}>150</option>
            <option value="200" {{($data->qte_alert=="200") ? "selected" : ""}}>200</option>
            <option value="300" {{($data->qte_alert=="300") ? "selected" : ""}}>300</option>

            <option value="400" {{($data->qte_alert=="400") ? "selected" : ""}}>400</option>
            <option value="500" {{($data->qte_alert=="500") ? "selected" : ""}}>500</option>
            <option value="1000" {{($data->qte_alert=="1000") ? "selected" : ""}}>1000</option>
            <option value="1500" {{($data->qte_alert=="1500") ? "selected" : ""}}>1500</option>
        </select>
        @error("qte_alert")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>
      <div class="row g-3">
      <div class="col-md-4">

        <div class="form-group">
          <label for="contact_user">Side bar</label>
          <input type="color" name="sidebar" value="{{$data->sidebar}}" class="form-control montant @error('sidebar') is-invalid @enderror" id="sidebar" placeholder="La couleur: ex: #f3f3f;" required>
          @error("sidebar")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">

        <div class="form-group">
          <label for="contact_user">Navbar</label>
          <input type="color" name="navbar" value="{{$data->navbar}}" class="form-control montant @error('navbar') is-invalid @enderror" id="sidebar" placeholder="La couleur: ex: #f3f3f;" required>
          @error("navbar")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="logo">Page de connexion</label>
        <input type="color" name="login" value="{{$data->login}}" class="form-control montant @error('login') is-invalid @enderror" id="login" placeholder="La couleur: ex: #f3f3f;" >
        @error("login")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>
      <h4 class="text-center mt-3">Configuration SMS</h4> <hr>
<div class="row g-3">
        <div class="col-md-2">
          <div class="form-group">
            <label for="name_user">Option SMS </label>
            <select name="sms" class="form-control @error('sms') is-invalid @enderror" id="sms">
              <option value=""></option>
              <option value="OUI"{{($data->sms=="OUI") ? 'selected':''}}>OUI</option>
              <option value="NON"{{($data->sms=="NON") ? 'selected':''}}>NON</option>
            </select>
            @error("name_user")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
          <div class="col-md-2">
          <div class="form-group">
            <label for="name_user">Option Code bar </label>
            <select name="codebar" class="form-control @error('codebar') is-invalid @enderror" id="codebar">
              <option value=""></option>
              <option value="OUI"{{($data->codebar=="OUI") ? 'selected':''}}>OUI</option>
              <option value="NON"{{($data->codebar=="NON") ? 'selected':''}}>NON</option>
            </select>
            @error("codebar")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="name_user">Sender Name </label>
            <input type="text" name="senderName" value="{{$data->senderName}}" class="form-control @error('senderName') is-invalid @enderror" id="senderName" placeholder="Saisissez le sender Name">
            @error("senderName")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="email">Email</label>
            <input type="text" name="email" value="{{$data->email}}" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Saisissez le email">
            @error("email")
                <span class="text-danger"> {{$message}}</span>
            @enderror
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="password">Password</label>
          <input type="text" name="password" value="{{$data->password}}" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Saisissez le mot de passe ">
            @error("password")
                <span class="text-danger"> {{$message}}</span>
            @enderror
    </div>
  </div>
    </div>
    <div class="row g-3">
       <div class="col-md-6">
        <div class="form-group">
          <label for="msgAchat">Message d'achat</label>
          <textarea name="msgAchat" class="form-control @error('msgAchat') is-invalid @enderror" id="msgAchat" cols="2" rows="2">{{$data->msgAchat}}</textarea>
          @error("msgAchat")
              <span class="text-danger"> {{$message}}</span>
          @enderror
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="msgAnnuler">Message d'annuler</label>
        <textarea name="msgAnnuler" class="form-control @error('msgAnnuler') is-invalid @enderror" id="msgAnnuler" cols="2" rows="2">{{$data->msgAnnuler}}</textarea>
        @error("msgAnnuler")
            <span class="text-danger"> {{$message}}</span>
        @enderror
    </div>
  </div>
    </div>
    <h4 class="text-center mt-3">Configuration de message</h4> <hr>
      <div class="row g-3">
        <div class="col-md-3">
          <div class="form-group">
            <label for="date_fin">Fin d'abonnement</label>
            <input type="date" value="{{$data->date_fin}}" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" placeholder="Saisissez ">
            @error("date_fin")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
        <div class="col-md-9">
          <div class="form-group">
            <label for="warning_message">Message d'avertissement</label>
            <textarea name="warning_message" class="form-control @error('warning_message') is-invalid @enderror" id="warning_message" cols="1" rows="2">{{$data->warning_message}}</textarea>
            @error("warning_message")
                <span class="text-danger"> {{$message}}</span>
            @enderror
        </div>
      </div>
    </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Enregistrer</button>
          <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route("settings.index") }}"> Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
