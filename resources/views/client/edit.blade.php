@extends('layout.main')

@section('main')
    <div class="row justify-content-center">
        <div class="col-lg-12">

            <div class="card card-primary shadow-lg">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-edit mr-2"></i>
                        <strong>Modification d'un client</strong>
                    </h3>
                </div>

                <form action="{{ route('client.update', [$datas->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="row">

                            <!-- Nom -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom complet</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>

                                        <input type="nom" name="nom" value="{{ $datas->nom }}"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            placeholder="Nom du client">
                                    </div>

                                    @error('nom')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                        </div>

                                        <input type="contact" name="contact" value="{{ $datas->contact }}"
                                            class="form-control @error('contact') is-invalid @enderror"
                                            placeholder="Numéro de téléphone">
                                    </div>

                                    @error('contact')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Adresse -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Adresse</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                        </div>

                                        <input type="text" name="adresse" value="{{ old('adresse', $datas->adresse) }}"
                                            class="form-control @error('adresse') is-invalid @enderror"
                                            placeholder="Adresse du client">
                                    </div>

                                    @error('adresse')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>

                                        <input type="email" name="email" value="{{ old('email', $datas->email) }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="email@example.com">
                                    </div>

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Limite de Crédit -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Limite de Crédit</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-credit-card"></i>
                                            </span>
                                        </div>

                                        <input type="number" name="credit_limit"
                                            value="{{ old('credit_limit', $datas->credit_limit) }}"
                                            class="form-control @error('credit_limit') is-invalid @enderror"
                                            placeholder="0.00" min="0" step="0.01">
                                    </div>

                                    @error('credit_limit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">

                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mettre à jour les informations du client.
                            </small>

                            <div>
                                <a href="{{ route('client.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Retour
                                </a>

                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-save mr-1"></i>
                                    Mettre à jour
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection