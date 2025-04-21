@extends('layouts.app')
@section('title', 'Liste des examens')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="examenSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Suivi des resultats examens / moyennes / consultation
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterExamen">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        @click="showModal = true">
                                        <i class="fa fa-add"></i> Création
                                    </button>
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
                                                <th class="min-w-125px">Nom Examen</th>
                                                <th class="min-w-125px">Type Examen</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Date de début</th>
                                                <th class="min-w-125px">Date de fin</th>
                                                <th class="min-w-125px">Clôture</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="examen in paginatedExamens" :key="examen.id">
                                                <tr>
                                                    <td x-text="examen.name"></td>

                                                    <td x-text="examen.type_examen.name"></td>
                                                    <td x-text="examen.annee_academique.name"></td>
                                                    <td x-text="examen.classe.name"></td>
                                                    <td x-text="new Date(examen.date_debut).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td x-text="new Date(examen.date_fin).toLocaleDateString('fr-FR')"></td>
                                                    <td x-text="examen.cloture ? 'Oui' : 'Non'"></td>

                                                    <td class="text-end d-flex justify-content-start">


                                                        <a :href="`{{ route('examens.managementgrade.show', ['managementgrade' => '__ID__']) }}`
                                                        .replace(
                                                            '__ID__', examen.id)"
                                                            class="btn btn-warning btn-sm" title="Consulter le rapport">
                                                            <i class="fa fa-eye"></i>

                                                        </a>
                                                        &nbsp; &nbsp;
                                                        <a :href="`{{ route('examens.create.repartition', ['id' => '__ID__']) }}`
                                                        .replace
                                                            ('__ID__', examen.id)"
                                                            class="btn btn-info btn-sm" title="Imprimer le bulletin">
                                                            <i class="fa fa-file-text"></i>
                                                        </a>

                                                    </td>

                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        function examenSearch() {
            return {
                searchTerm: '',
                examens: @json($listeexamens),
                filteredExamens: [],
                currentPage: 1,
                examensPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    nom: '',
                    description: '',
                    typeexamen_id: '',
                    anneeacademique_id: '',
                    classe_id: '',
                    date_debut: '',
                    date_fin: '',
                    cloture: false,
                },
                currentExamen: null,
                typeExamens: @json($typexamen),
                anneeAcademiques: @json($anneAcademique),
                classes: @json($classe),


                filterExamen() {
                    this.filteredExamens = this.examens.filter(examen => {
                        return examen.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            examen.description.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                },

                get paginatedExamens() {
                    let start = (this.currentPage - 1) * this.examensPerPage;
                    let end = start + this.examensPerPage;
                    return this.filteredExamens.slice(start, end);
                },

                init() {
                    this.filterExamen();
                    this.isLoading = false;
                },
            };
        }
    </script>
@endsection
