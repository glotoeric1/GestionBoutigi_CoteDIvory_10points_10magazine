@extends('layout.main')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modification d'un client</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('client.update', [$datas->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="form-group">
                            <label for="exampleInputPassword1">Nom</label>
                            <input type="nom" name="nom" value="{{ $datas->nom }}"
                                class="form-control @error('nom') is-invalid @enderror" id="exampleInputPassword1"
                                placeholder="Nom">
                            @error('nom')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Contact</label>
                            <input type="contact" name="contact" value="{{ $datas->contact }}"
                                class="form-control @error('contact') is-invalid @enderror" id="exampleInputPassword1"
                                placeholder="Contact">
                            @error('contact')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Adresse</label>
                            <input type="adresse" name="adresse" value="{{ $datas->adresse }}"
                                class="form-control @error('adresse') is-invalid @enderror" id="exampleInputPassword1"
                                placeholder="Adresse">
                            @error('adresse')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" placeholder="email">
                            @error('email')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary mx-2 rounded-pill px-4">Submit</button>
                        <a class="btn btn-outline-warning mx-2 rounded-pill px-4" href="{{ route('client.index') }}"> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
