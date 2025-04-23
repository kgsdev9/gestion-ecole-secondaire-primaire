@extends('layouts.app')
@section('title', 'Gestion des notes des examens')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="gestionNotes()" x-init="init()">
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
                        MOYENNES DE L'EXAMEN :
                        <span class="text-primary ms-2" x-text="`${examen.name} (${examen.code})`"></span>
                    </h1>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <template x-if="isLoading">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </template>

                        <template x-if="!isLoading">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th>Élève</th>
                                        <template x-for="matiere in matieres" :key="matiere.matiere.id">
                                            <th x-text="matiere.matiere.name"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    <template x-for="(eleve, index) in eleves" :key="eleve.id">
                                        <tr>
                                            <td x-text="eleve.nom"></td>
                                            <template x-for="matiere in matieres" :key="matiere.id">
                                                <td>
                                                    <input type="number" step="0.01" min="0" max="20"
                                                        class="form-control form-control-sm"
                                                        x-model="notes[eleve.id][matiere.matiere.id]" />
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <button @click="saveNotes" class="btn btn-primary btn-sm">
                            <i class="fa fa-save"></i> Enregistrer
                        </button>
                    </div>
                    <div>
                        <a href="{{route('examens.moyenne.index')}}" class="btn btn-light btn-sm" >
                            <i class="fa fa-arrow-left me-1"></i> Retourner
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function gestionNotes() {
            return {
                examen: @json($examen),
                eleves: @json($eleves),
                matieres: @json($programmexamens),
                notes: {},
                isLoading: false,

                init() {
                    this.eleves.forEach(eleve => {
                        this.notes[eleve.id] = {};
                        this.matieres.forEach(matiere => {
                            this.notes[eleve.id][matiere.matiere_id] = '';
                        });
                    });
                },

                saveNotes() {

                    fetch('{{ route('examens.moyenne.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                examen_id: this.examen.id,
                                code: this.examen.code,
                                notes: this.notes
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.isLoading = false;
                            Swal.fire({
                                icon: 'success',
                                title: 'Notes enregistrées avec succès',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            this.isLoading = false;
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur est survenue lors de l\'enregistrement.'
                            });
                        });
                }
            }
        }
    </script>
@endsection
