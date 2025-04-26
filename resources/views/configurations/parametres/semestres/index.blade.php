@extends('layouts.app')
@section('title', 'Paramètre du semestre | trimestre')
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid" x-data="parametreSemestre()" x-init="init()">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <!-- En-tête -->
        <div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10"
            style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('/keen/demo1/assets/media/illustrations/sketchy-1/4.png')">
            <div class="card-header pt-10">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-circle me-5">
                        <div class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                            <i class="ki-duotone ki-abstract-47 fs-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h2 class="mb-1">Paramètre des semestres | trimestres</h2>
                        <div class="text-muted fw-bold">
                            <a href="#">Paramètre de configuration associé aux semestres</a> <span class="mx-3">|</span>
                            <a href="#">Rapport semestre | trimestre</a> <span class="mx-3">|</span>
                            Attention : toute modification affecte les résultats finaux.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card card-flush">
            <div class="card-header pt-8">
                <div class="card-title">
                    <h2>Préférences</h2>
                </div>
            </div>

            <div class="card-body">
                <div class="form">
                    <div class="fv-row row mb-15">
                        <div class="col-md-3 d-flex align-items-center">
                            <label class="fs-6 fw-semibold">Sélectionner le semestre</label>
                        </div>
                        <div class="col-md-4">
                            <select x-model="code" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0">
                                <option value="">Sélectionner un semestre</option>
                                <template x-for="semestre in semestres" :key="semestre.id">
                                    <option :value="semestre.id" x-text="semestre.name"></option>
                                </template>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <select x-model="classe_id" class="form-select form-select-lg form-select-solid mb-3 mb-lg-0">
                                <option value="">Sélectionner une classe </option>
                                <template x-for="classe in classes" :key="classe.id">
                                    <option :value="classe.id" x-text="classe.name"></option>
                                </template>
                            </select>
                        </div>

                    </div>

                    <div class="fv-row row mb-15">
                        <div class="col-md-3 d-flex align-items-center">
                            <label class="fs-6 fw-semibold">Clôturer</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-check form-check-custom form-check-solid me-10">
                                <input class="form-check-input" type="checkbox" id="cloturer_switch" x-model="cloturer" @change="handleCloturerChange()" :disabled="cloturer === 1 && (decloturer === 1 || rapport === 1)">
                                <label class="form-check-label" for="cloturer_switch">Marquer le semestre comme terminé.</label>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row row mb-15">
                        <div class="col-md-3 d-flex align-items-center">
                            <label class="fs-6 fw-semibold">Déclôturer</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-check form-check-custom form-check-solid me-10">
                                <input class="form-check-input" type="checkbox" id="decloturer_switch" x-model="decloturer" @change="handleDecloturerChange()" :disabled="decloturer === 1 && (cloturer === 1 || rapport === 1)">
                                <label class="form-check-label" for="decloturer_switch">Réouvrir le semestre.</label>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row row mb-15">
                        <div class="col-md-3 d-flex align-items-center">
                            <label class="fs-6 fw-semibold">Générer le rapport</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-check form-check-custom form-check-solid me-10">
                                <input class="form-check-input" type="checkbox" id="rapport_switch" x-model="rapport" @change="handleRapportChange()" :disabled="rapport === 1 && (cloturer === 1 || decloturer === 1)">
                                <label class="form-check-label" for="rapport_switch">Créer un document PDF du semestre.</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-12">
                        <div class="col-md-9 offset-md-3">
                            <button type="button" @click="submitAction()" class="btn btn-primary" :disabled="isLoading">
                                <template x-if="!isLoading">
                                    <span>Soumettre</span>
                                </template>
                                <template x-if="isLoading">
                                    <span>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Chargement...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function parametreSemestre() {
        return {
            code: '',
            semestres:@json($semestres),
            classes:@json($classes),
            classe_id:'',
            cloturer: 0,
            decloturer: 0,
            rapport: 0,
            isLoading: false,

            init() {
                console.log('Init semestre lancé');
            },

            handleCloturerChange() {
                this.cloturer = this.cloturer ? 1 : 0;
                if (this.cloturer) {
                    this.decloturer = 0;
                    this.rapport = 0;
                }
            },

            handleDecloturerChange() {
                this.decloturer = this.decloturer ? 1 : 0;
                if (this.decloturer) {
                    this.cloturer = 0;
                    this.rapport = 0;
                }
            },

            handleRapportChange() {
                this.rapport = this.rapport ? 1 : 0;
                if (this.rapport) {
                    this.cloturer = 0;
                    this.decloturer = 0;
                }
            },

            async submitAction() {
                this.isLoading = true;

                if (!this.code || this.code.trim() === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Le code est requis.',
                        showConfirmButton: true
                    });
                    this.isLoading = false;
                    return;
                }

                if ((this.cloturer && (this.decloturer || this.rapport)) ||
                    (this.decloturer && (this.cloturer || this.rapport)) ||
                    (this.rapport && (this.cloturer || this.decloturer))) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Vous ne pouvez pas sélectionner plusieurs actions à la fois.',
                        showConfirmButton: true
                    });
                    this.isLoading = false;
                    return;
                }

                const formData = new FormData();
                formData.append('code', this.code);
                formData.append('classe_id', this.classe_id);

                if (this.cloturer) {
                    formData.append('cloturer', this.cloturer);
                } else if (this.decloturer) {
                    formData.append('decloturer', this.decloturer);
                } else if (this.rapport) {
                    formData.append('rapport', this.rapport);
                }

                try {
                    const response = await fetch('{{ route('configurationnote.action.semestre') }}', { // ⚡⚡⚡ Change bien la route ici !
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData,
                    });

                    if (response.ok) {
                        const data = await response.json();
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur serveur.',
                            showConfirmButton: true
                        });
                    }
                } catch (error) {
                    console.error('Erreur réseau :', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Semestre non trouvé.',
                        showConfirmButton: true
                    });
                } finally {
                    this.isLoading = false;
                }
            },
        }
    }
</script>
@endpush
@endsection
