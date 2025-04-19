@extends('layouts.app')

@section('title', 'Programme examen')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="programmeexamenSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Planification du programme des examens
                        </h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class='fas fa-search position-absolute ms-5'></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterProgrammeExamen">
                                </div>
                            </div>
                        </div>

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
                                            <template x-for="(programme, index) in programmeexamen" :key="programme.id || index">
                                                <tr>
                                                    <td>
                                                        <select class="form-control" x-model="programme.matiere_id" required>
                                                            <option value="">Sélectionner une matière</option>
                                                            <template x-for="matiere in matieres" :key="matiere.id">
                                                                <option :value="matiere.id" x-text="matiere.name"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" x-model="programme.heure_debut" required>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" x-model="programme.heure_fin" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" x-model="programme.duree" required>
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

                        <div class="card-footer d-flex justify-content-end">
                            <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                @click="addRow()">
                                <i class="fa fa-plus"></i> Ajouter une ligne
                            </button>
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
                programmeexamen: @json($programmeexamens), // Données initiales des programmes
                isLoading: false,
                matieres: @json($matieres), // Assure-toi d'envoyer les matières dans la vue

                // Ajouter une ligne vide dans le tableau
                addRow() {
                    this.programmeexamen.push({
                        matiere_id: '',
                        heure_debut: '',
                        heure_fin: '',
                        duree: '',
                    });
                },

                // Supprimer une ligne à l'index donné
                deleteRow(index) {
                    this.programmeexamen.splice(index, 1);
                },

                // Appliquer un filtrage basé sur le terme de recherche
                filterProgrammeExamen() {
                    this.programmeexamen = this.programmeexamen.filter(programme => {
                        return programme.matiere_id && this.matieres.some(matiere =>
                            matiere.id === programme.matiere_id && matiere.nom.toLowerCase().includes(this.searchTerm.toLowerCase())
                        );
                    });
                },

                init() {
                    this.isLoading = false;
                },
            };
        }
    </script>
@endsection
