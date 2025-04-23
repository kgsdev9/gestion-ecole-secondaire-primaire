@extends('layouts.app')
@section('title', 'Parametre du semestre | trimestre')
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10"
                style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('/keen/demo1/assets/media/illustrations/sketchy-1/4.png')">
                <div class="card-header pt-10">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-circle me-5">
                            <div class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                                <i class="ki-duotone ki-abstract-47 fs-2x text-primary"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h2 class="mb-1">Paramètre des semestres | trimestre </h2>
                            <div class="text-muted fw-bold">
                                <div class="text-muted fw-bold">
                                    <a href="#">Paramètre de configuration associé aux examens</a> <span
                                        class="mx-3">|</span>
                                    <a href="#">Rapport semestre | trimestre</a> <span class="mx-3">|</span>
                                    La génération du rapport prend en compte les notes et le statut du semestre ou
                                    trimestre. Toute modification peut affecter le rapport final.
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="d-flex overflow-auto h-55px"></div>
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-header pt-8">
                    <div class="card-title">
                        <h2>Préférences</h2>
                    </div>
                </div>

                <div class="card-body">
                    <form class="form" id="kt_file_manager_settings">
                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Code du semestre</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Clôturer le semestre</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="cloturer_switch">
                                    <label class="form-check-label" for="cloturer_switch">
                                        Marquer le semestre comme terminé pour empêcher toute modification.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Déclôturer le semestre</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="decloturer_switch">
                                    <label class="form-check-label" for="decloturer_switch">
                                        Réouvrir le semestre pour autoriser des modifications.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Générer le rapport du semestre</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="rapport_switch">
                                    <label class="form-check-label" for="rapport_switch">
                                        Créer un document PDF contenant les informations du semestre.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-12">
                            <div class="col-md-9 offset-md-3">
                                <button type="button" class="btn btn-primary">Soumettre</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
