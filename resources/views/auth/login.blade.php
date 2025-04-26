<!DOCTYPE html>
<html lang="fr">

<head>
    <base href="../../../" />
    <title>Connexion à l'Application Scolaire</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
</head>

<body id="kt_body" class="app-blank app-blank">

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!-- Colonne image -->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-no-repeat bgi-position-center"
                style="background-image: url('{{ asset('image-2.jpg') }}'); background-size: cover;">

            </div>

            <!-- Colonne formulaire -->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 justify-content-center align-items-center">
                <div class="w-lg-500px p-10 bg-light rounded shadow">
                    <form class="form w-100" novalidate="novalidate" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">CONNECTEZ-VOUS À L'APPLICATION SCOLAIRE</h1>
                            <div class="text-gray-500 fw-semibold fs-6">
                                Accédez aux fonctionnalités de gestion des élèves, des enseignants et des emplois du temps
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="fv-row mb-8 form-floating">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <label for="email">Email</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="fv-row mb-8 form-floating">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password">
                            <label for="password">Mot de passe</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Bouton -->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Connexion</span>
                            </button>
                        </div>

                        <!-- Lien inscription -->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            Vous n'avez pas de compte ?
                            <a href="{{ route('register') }}" class="link-primary fw-semibold">Inscrivez-vous ici</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Fin formulaire -->
        </div>
    </div>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>
