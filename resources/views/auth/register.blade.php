@extends('layouts.guest')

@section('content')
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Créer un nouveau compte</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group mb-4">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nom complet" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-4">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Adresse Email" value="{{ old('email') }}" required autocomplete="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Mot de passe" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-4">
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirmer le mot de passe" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                J'accepte les <a href="#" class="text-primary font-weight-bold">conditions
                                    d'utilisation</a>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center">
                <p class="mb-0">
                    <a href="{{ route('login') }}" class="text-center font-weight-bold">J'ai déjà un compte</a>
                </p>
            </div>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
@endsection
