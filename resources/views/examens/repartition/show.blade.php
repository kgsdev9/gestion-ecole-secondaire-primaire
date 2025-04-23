@extends('layouts.app')
@section('title', 'Répartition des élèves aux examens')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="repartitionForm()" x-init="init()">
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
                        RÉPARTITION DES ÉLÈVES À L'EXAMEN : <span x-text="examen.name" class="text-primary ms-2"></span>
                    </h1>

                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-solid w-250px ps-13 form-control-sm"
                            placeholder="Rechercher un eleve" x-model="searchTerm" @input="filterData">
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <button @click="printRepartition" class="btn btn-light-primary btn-sm">
                                <i class="fa fa-print"></i> Imprimer
                            </button>
                            <button @click="exportRepartitoon" class="btn btn-light-primary btn-sm">
                                <i class='fas fa-file-export'></i> Export
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
                                        <th>Élève</th>
                                        <th>Matricule</th>
                                        <th>Salle</th>

                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    <template x-for="item in paginatedData" :key="item.id">
                                        <tr>
                                            <td x-text="item.eleve.nom"></td>
                                            <td x-text="item.eleve.matricule"></td>
                                            <td x-text="item.salle.name"></td>

                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                        <button class="page-link" @click="goToPage(currentPage - 1)">Précédent</button>
                                    </li>
                                    <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                        <button class="page-link" @click="goToPage(currentPage + 1)">Suivant</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function repartitionForm() {
            return {
                searchTerm: '',
                allData: @json($repartitions),
                examen: @json($examen),
                filteredData: [],
                currentPage: 1,
                perPage: 10,
                totalPages: 0,
                isLoading: false,

                get paginatedData() {
                    return this.filteredData.slice((this.currentPage - 1) * this.perPage, this.currentPage * this
                        .perPage);
                },

                filterData() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredData = this.allData.filter(item =>
                        item.eleve.nom.toLowerCase().includes(term) ||
                        item.eleve.matricule.toLowerCase().includes(term) ||
                        item.salle.name.toLowerCase().includes(term)
                    );
                    this.totalPages = Math.ceil(this.filteredData.length / this.perPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                init() {
                    this.filteredData = this.allData;
                    this.totalPages = Math.ceil(this.filteredData.length / this.perPage);
                },
            };
        }
    </script>
@endsection
