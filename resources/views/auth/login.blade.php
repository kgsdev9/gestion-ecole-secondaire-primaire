<!DOCTYPE html>

<html lang="fr">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>Connexion à l'Application Scolaire - Gestion des Élèves et des Cours</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Connectez-vous à l'application scolaire pour gérer les élèves, suivre les notes, et organiser les emplois du temps. Optimisez la gestion de votre école." />
    <meta name="keywords"
        content="gestion scolaire, école, gestion des élèves, gestion des cours, plateforme scolaire, emploi du temps, suivi des notes" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Connexion à l'Application Scolaire - La Solution Complète pour Votre École" />
    <meta property="og:url" content="https://votre-application-ecole.com" />
    <meta property="og:site_name" content="Application Scolaire" />
    <link rel="canonical" href="https://votre-application-ecole.com" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>

<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank app-blank">

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-up -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
               <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center"
                style="background-image:url({{ asset('auth-bg-school.jpg') }})">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center p-6 p-lg-10 w-100">
                    <!--begin::Logo-->
                    <a href="#" class="mb-0 mb-lg-20">
                        <img alt="Logo" src="{{ asset('school-logo.png') }}" class="h-40px h-lg-50px" />
                        <img alt="Logo" src="{{ asset('school-logo.png') }}" class="h-40px h-lg-50px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Image-->
                    <img class="d-none d-lg-block mx-auto w-300px w-lg-75 w-xl-500px mb-10 mb-lg-20"
                        src="assets/media/misc/school-login.png" alt="" />
                    <!--end::Image-->
                    <!--begin::Title-->
                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bold text-center mb-7">Gérez efficacement votre école avec notre plateforme</h1>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="d-none d-lg-block text-white fs-base text-center">
                        Connectez-vous pour gérer les élèves, les enseignants, les emplois du temps, et plus encore.
                        <a href="#" class="opacity-75-hover text-warning fw-semibold me-1">L'application</a> vous permet de suivre les performances des élèves, gérer les inscriptions et optimiser la communication au sein de votre établissement.
                        <br />Accédez à des outils puissants pour simplifier la gestion de votre école et améliorer la qualité de l'enseignement.
                        <br />Commencez dès aujourd'hui à optimiser la gestion de votre école grâce à notre application innovante.
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10">

                        <form class="form w-100" novalidate="novalidate" method="POST" action="{{ route('login') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">CONNECTEZ-VOUS À L'APPLICATION SCOLAIRE</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6">Accédez aux fonctionnalités de gestion des élèves, des enseignants et des emplois du temps</div>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Heading-->

                            <!-- Email Input -->
                            <div class="fv-row mb-8 form-floating">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <label for="email" class="form-label">Email</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="fv-row mb-8 form-floating" data-kt-password-meter="true">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                <label for="password" class="form-label">Mot de passe</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Connexion</span>
                                </button>
                            </div>

                            <!-- Sign Up Link -->
                            <div class="text-gray-500 text-center fw-semibold fs-6">Vous n'avez pas de compte ?
                                <a href="{{ route('register') }}" class="link-primary fw-semibold">Inscrivez-vous ici</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>

</html>
