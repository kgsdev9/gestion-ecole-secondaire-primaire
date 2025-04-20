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
                            <span x-text="examen.nom" class="text-primary ms-2"></span>
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
                                                <th class="min-w-125px">Heure de début</th>
                                                <th class="min-w-125px">Heure de fin</th>
                                                <th class="min-w-125px">Durée</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="(programme, index) in programmeexamen" :key="index">

                                                :key="programme.id || index">
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
                                                        <input type="time" class="form-control"
                                                            x-model="programme.heure_debut" required>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control"
                                                            x-model="programme.heure_fin" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" x-model="programme.duree"
                                                            required>
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
                            <!-- Bouton Enregistrer à gauche -->
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
                        matiere_id: '',
                        heure_debut: '',
                        heure_fin: '',
                        duree: '',
                    });
                },

                deleteRow(index) {
                    this.programmeexamen.splice(index, 1);
                },

                filterProgrammeExamen() {
                    this.programmeexamen = this.programmeexamen.filter(programme => {
                        return programme.matiere_id && this.matieres.some(matiere =>
                            matiere.id === programme.matiere_id && matiere.nom.toLowerCase().includes(this
                                .searchTerm.toLowerCase())
                        );
                    });
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
