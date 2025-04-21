@extends('layouts.app')

@section('title', 'Programme examen')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="programmeexamenSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 justify-content-center my-0">
                            Planification du programme des examens :
                            <span x-text="examen.name" class="text-primary ms-2"></span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">
                        <div class="card-body py-4">
                            <div class="table-responsive">
                                <template x-if="isLoading">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!isLoading">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-125px">Matière</th>
                                                <th class="min-w-125px">Jour</th>
                                                <th class="min-w-125px">Heure de début</th>
                                                <th class="min-w-125px">Heure de fin</th>
                                                <th class="min-w-125px">Durée (heures)</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="(programme, index) in programmeexamen" :key="programme.id">
                                                <tr>
                                                    <td>
                                                        <select class="form-select" x-model="programme.matiere_id" required>
                                                            <option value="">Sélectionner une matière</option>
                                                            <template x-for="matiere in matieres" :key="matiere.id">
                                                                <option :value="matiere.id" x-text="matiere.name"
                                                                    :selected="programme.matiere_id == matiere.id"></option>
                                                            </template>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="text" class="form-control" x-model="programme.jour"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="time" class="form-control"
                                                            x-model="programme.heure_debut" @change="updateDuree(index)"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="time" class="form-control"
                                                            x-model="programme.heure_fin" @change="updateDuree(index)"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.01" class="form-control"
                                                            x-model="programme.duree" readonly>
                                                    </td>

                                                    <td class="text-end d-flex justify-content-start">
                                                        <button @click="deleteRow(index)"
                                                            class="btn btn-danger btn-sm mx-2">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <div>
                                <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                    @click="addRow()">
                                    <i class="fa fa-plus me-1"></i> Ajouter une ligne
                                </button>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-sm" @click="submitProgrammeExamen()">
                                    <i class="fa fa-save me-1"></i> Enregistrer le programme
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function programmeexamenSearch() {
            return {
                searchTerm: '',
                programmeexamen: @json($programmeexamens),
                examen: @json($examen),
                isLoading: false,
                matieres: @json($matieres),

                addRow() {
                    this.programmeexamen.push({
                        id: Date.now(), 
                        matiere_id: '',
                        jour: '',
                        heure_debut: '',
                        heure_fin: '',
                        duree: '',
                    });
                },


                deleteRow(index) {
                    this.programmeexamen.splice(index, 1);
                },

                updateDuree(index) {
                    const programme = this.programmeexamen[index];
                    if (programme.heure_debut && programme.heure_fin) {
                        const debut = new Date(`1970-01-01T${programme.heure_debut}:00`);
                        const fin = new Date(`1970-01-01T${programme.heure_fin}:00`);

                        const diffMs = fin - debut;
                        const diffHeures = diffMs / (1000 * 60 * 60); // convert ms to hours

                        if (diffHeures > 0) {
                            programme.duree = diffHeures.toFixed(2);
                        } else {
                            programme.duree = 0;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Heure invalide',
                                text: 'L\'heure de fin doit être après l\'heure de début.',
                                showConfirmButton: true,
                            });
                        }
                    }
                },


                init() {
                    this.isLoading = false;
                },

                submitProgrammeExamen() {
                    const formData = new FormData();
                    formData.append('examen_id', this.examen.id);
                    formData.append('programmeexamen', JSON.stringify(this.programmeexamen));

                    fetch('{{ route('examens.programme.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur lors de l\'enregistrement');
                            }
                            return response.json();
                        })
                        .then(result => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Programme enregistré avec succès !',
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            window.location.href =
                            '{{ route('examens.gestion.index') }}'; // change la route si nécessaire
                        })
                        .catch(error => {
                            console.error('Erreur réseau:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Une erreur est survenue.',
                                text: error.message,
                                showConfirmButton: true,
                            });
                        });
                }
            };
        }
    </script>
@endsection
