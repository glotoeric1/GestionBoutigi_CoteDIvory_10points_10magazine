@extends('layout.main')
@section('main')

    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modification d'un magasin</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('entrepot.update', [$data->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nom_entrepot">Nom magasin</label>
                                    <input type="text" name="nom_entrepot" value="{{ $data->nom_entrepot }}"
                                        class="form-control @error('nom_entrepot') is-invalid @enderror">
                                    @error('nom_entrepot')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Entreprise</label>
                                    @if (Auth::user()->id == 1)
                                        <select name="id_setting" id="id_setting"
                                            class="form-control @error('id_setting') is-invalid @enderror">
                                            <option value="">Choisir entreprise</option>
                                            @foreach ($settings as $item)
                                                <option value="{{ $item->id }}" {{ $data->id_setting == $item->id ? 'selected' : '' }}>
                                                    {{ $item->app_name }} - {{ $item->contact }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        @php
                                            $setting = \App\Models\settings::find(Auth::user()->id_setting);
                                        @endphp
                                        <input type="hidden" name="id_setting" value="{{ Auth::user()->id_setting }}">
                                        <input type="text" value="{{ $setting->app_name }} - {{ $setting->contact }}"
                                            class="form-control" readonly>
                                    @endif
                                    @error('id_setting')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Point de vente</label>
                                    @if (Auth::user()->id == 1)
                                        <select name="id_boutique" id="id_boutique"
                                            class="form-control @error('id_boutique') is-invalid @enderror">
                                            <option value="">Choisir un point de vente</option>
                                            @foreach ($boutiques as $boutique)
                                                <option value="{{ $boutique->id }}" {{ $data->id_boutique == $boutique->id ? 'selected' : '' }}>
                                                    {{ $boutique->nom_boutique }} - {{ $boutique->contact }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        @php
                                            $id_boutique = session('selected_boutique_id');
                                            $name_boutique = session('selected_boutique_name');
                                        @endphp
                                        <input type="hidden" name="id_boutique" value="{{ $id_boutique }}">
                                        <input type="text" value="{{ $name_boutique }}" class="form-control" readonly>
                                    @endif
                                    @error('id_boutique')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                        <a class="btn btn-warning" href="{{ route('entrepot.index') }}"> Annuler</a>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->


        </div>
    </div>
    </div>
@endsection
