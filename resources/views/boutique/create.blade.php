@extends('layout.main')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ajout d'un point de vente</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('boutique.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nom_boutique">Nom du point de vente</label>
                                    <input type="nom_boutique" name="nom_boutique" value="{{ old('nom_boutique') }}"
                                        class="form-control @error('nom_boutique') is-invalid @enderror"
                                        id="exampleInputEmail1" placeholder="">
                                    @error('nom_boutique')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Adresse</label>
                                    <input type="adresse" name="adresse" value="{{ old('adresse') }}"
                                        class="form-control @error('adresse') is-invalid @enderror"
                                        id="exampleInputPassword1" placeholder="">
                                    @error('adresse')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Contact</label>
                                    <input type="contact" name="contact" value="{{ old('contact') }}"
                                        class="form-control @error('contact') is-invalid @enderror"
                                        id="exampleInputPassword1" placeholder="Contact">
                                    @error('contact')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Logo</label>
                                    <input type="file" name="logo" value="{{ old('logo') }}"
                                        class="form-control @error('logo') is-invalid @enderror" id="exampleInputPassword1"
                                        placeholder="Logo...">
                                    @error('logo')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="gerant_boutique">Nom Gerant</label>
                                    <input type="gerant_boutique" name="gerant_boutique"
                                        value="{{ old('gerant_boutique') }}"
                                        class="form-control @error('gerant_boutique') is-invalid @enderror"
                                        id="exampleInputEmail1" placeholder="">
                                    @error('gerant_boutique')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Contact Gerant</label>
                                    <input type="contact_gerant" value="{{ old('contact_gerant') }}" name="contact_gerant"
                                        class="form-control @error('contact_gerant') is-invalid @enderror"
                                        id="exampleInputPassword1" placeholder="contact_gerant">
                                    @error('contact_gerant')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Entreprise</label>
                                    <select name="id_setting" id="id_setting"
                                        class="form-control @error('id_setting') is-invalid @enderror">
                                        <option value="">Choisir entreprise</option>
                                        @foreach ($settings as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->app_name }} - {{ $item->contact }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_setting')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                        <a class="btn btn-warning" href="{{ route('boutique.index') }}"> Annuler</a>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection
