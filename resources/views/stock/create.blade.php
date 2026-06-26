@extends('layout.main')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ajout d'un produit</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('stock.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="libelle">Nom produit</label>
                                    <input type="text" name="libelle" value="{{ old('libelle') }}"
                                        class="form-control @error('libelle') is-invalid @enderror">
                                    @error('libelle')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Entreprise</label>
                                    @if (Auth::user()->id == 1)
                                        <select name="id_setting" id="id_setting"
                                            class="form-control @error('id_setting') is-invalid @enderror">
                                            <option value="">Choisir entreprise</option>
                                            @foreach ($settings as $item)
                                                <option value="{{ $item->id }}">
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
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                        <a class="btn btn-warning" href="{{ route('stock.index') }}"> Annuler</a>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection
