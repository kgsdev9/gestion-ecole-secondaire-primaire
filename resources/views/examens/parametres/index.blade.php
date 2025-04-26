@extends('layouts.app')
@section('title', 'Parametre des examens')
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid" x-data="parametreExamen()" x-init="init()">
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
                            <h2 class="mb-1">Paramètre des examens</h2>
                            <div class="text-muted fw-bold">
                                <div class="text-muted fw-bold">
                                    <a href="#">Paramètre de configuration associé aux examens</a> <span
                                        class="mx-3">|</span>
                                    <a href="#">Rapport examen </a> <span class="mx-3">|</span>
                                    La génération du rapport prend en compte les notes et le statut des examens. Toute
                                    modification peut affecter le rapport final.
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
                    <div class="form">
                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Code de l'examen</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" x-model="code"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Clôturer</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="cloturer_switch" x-model="cloturer"
                                        @change="handleCloturerChange()"
                                        :disabled="cloturer === 1 && (decloturer === 1 || rapport === 1)">
                                    <label class="form-check-label" for="cloturer_switch">
                                        Marquer l'examen comme terminé pour empêcher toute modification.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Déclôturer</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="decloturer_switch"
                                        x-model="decloturer" @change="handleDecloturerChange()"
                                        :disabled="decloturer === 1 && (cloturer === 1 || rapport === 1)">
                                    <label class="form-check-label" for="decloturer_switch">
                                        Réouvrir l'examen pour autoriser des modifications.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="fv-row row mb-15">
                            <div class="col-md-3 d-flex align-items-center">
                                <label class="fs-6 fw-semibold">Générer le rapport</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input class="form-check-input" type="checkbox" id="rapport_switch" x-model="rapport"
                                        @change="handleRapportChange()"
                                        :disabled="rapport === 1 && (cloturer === 1 || decloturer === 1)">
                                    <label class="form-check-label" for="rapport_switch">
                                        Créer un document PDF contenant les informations de l'examen.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-12">
                            <div class="col-md-9 offset-md-3">
                                <button
                                    type="button"
                                    @click="submitAction()"
                                    class="btn btn-primary"
                                    :disabled="isLoading"
                                >
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
            function parametreExamen() {
                return {
                    code: '',
                    cloturer: 0,
                    decloturer: 0,
                    rapport: 0,
                    isLoading: false,

                    init() {
                        console.log('Init lancé');
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


                        // Vérifier qu'une seule action est sélectionnée
                        if (this.cloturer && (this.decloturer || this.rapport)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Vous ne pouvez pas sélectionner plusieurs actions à la fois.',
                                showConfirmButton: true
                            });
                            this.isLoading = false;
                            return;
                        }

                        if (this.decloturer && (this.cloturer || this.rapport)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Vous ne pouvez pas sélectionner plusieurs actions à la fois.',
                                showConfirmButton: true
                            });
                            this.isLoading = false;
                            return;
                        }

                        if (this.rapport && (this.cloturer || this.decloturer)) {
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

                        // Ajouter seulement l'action choisie
                        if (this.cloturer) {
                            formData.append('cloturer', this.cloturer);
                        } else if (this.decloturer) {
                            formData.append('decloturer', this.decloturer);
                        } else if (this.rapport) {
                            formData.append('rapport', this.rapport);
                        }


                        try {
                            const response = await fetch('{{ route('examens.execute.action') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                const message = data.message;

                                Swal.fire({
                                    icon: 'success',
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: message,
                                    showConfirmButton: true
                                });
                            }
                        } catch (error) {
                            console.error('Erreur réseau :', error);
                            Swal.fire({
                                icon: 'error',
                                title:'examen non trouvé',
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
