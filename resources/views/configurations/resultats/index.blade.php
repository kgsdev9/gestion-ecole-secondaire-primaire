@extends('layouts.app')
@section('title', 'Rapports Semestriels')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="rapportSemestreTable()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            RAPPORTS SEMESTRIELS
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterRapports">
                                </div>
                            </div>
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
                                                <th>Année</th>
                                                <th>Niveau</th>
                                                <th>Classe</th>
                                                <th>Semestre</th>
                                                <th>Nombre d'élèves</th>
                                                <th>Moyenne générale</th>
                                                <th>Taux de réussite</th>
                                                <th>Observations</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="rapport in paginatedRapports" :key="rapport.id">
                                                <tr>
                                                    <td x-text="rapport.anneeacademique?.name ?? 'N/A'"></td>
                                                    <td x-text="rapport.niveau?.name ?? 'N/A'"></td>
                                                    <td x-text="rapport.classe?.name?? 'N/A'"></td>
                                                    <td x-text="rapport.semestre?.name ?? 'N/A'"></td>
                                                    <td x-text="rapport.nombre_eleves"></td>
                                                    <td x-text="rapport.moyenne_generale"></td>
                                                    <td x-text="rapport.taux_reussite + '%'"></td>
                                                    <td x-text="rapport.observations"></td>
                                                    <td class="text-end">
                                                        <template x-if="rapport.semestre != 1">
                                                            <a
                                                                :href="`{{ route('administration.print.resultat.semestre', ['resultatsemestreId' => '__ID__']) }}`.replace('__ID__', rapport.id)"
                                                                class="btn btn-warning btn-sm"
                                                                title="Créer la répartition"
                                                            >
                                                            <i class="fa fa-print me-1"></i>
                                                            </a>
                                                        </template>

                                                        &nbsp;&nbsp;

                                                        <!-- Bouton "Visualisation de la répartition" (toujours affiché) -->
                                                        <a
                                                            :href="`{{ route('configurationnote.semestre.show', ['semestre' => '__ID__']) }}`.replace('__ID__', rapport.id)"
                                                            class="btn btn-light btn-sm"
                                                            title="Visualisation le resultat"
                                                        >
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </template>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-7 offset-md-5 d-flex justify-content-end">
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
        </div>
    </div>

    <script>
        function rapportSemestreTable() {
            return {
                searchTerm: '',
                rapports: @json($resultatsemestres),
                filteredRapports: [],
                currentPage: 1,
                rapportsPerPage: 10,
                totalPages: 0,
                isLoading: false,

                get paginatedRapports() {
                    return this.filteredRapports.slice((this.currentPage - 1) * this.rapportsPerPage, this.currentPage * this.rapportsPerPage);
                },

                filterRapports() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredRapports = this.rapports.filter(rapport =>
                        (rapport.anneeacademique?.nom ?? '').toLowerCase().includes(term) ||
                        (rapport.niveau?.nom ?? '').toLowerCase().includes(term) ||
                        (rapport.classe?.nom ?? '').toLowerCase().includes(term) ||
                        (rapport.semestre?.nom ?? '').toLowerCase().includes(term) ||
                        (rapport.observations ?? '').toLowerCase().includes(term)
                    );
                    this.totalPages = Math.ceil(this.filteredRapports.length / this.rapportsPerPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                init() {
                    this.filteredRapports = this.rapports;
                    this.totalPages = Math.ceil(this.filteredRapports.length / this.rapportsPerPage);
                }
            };
        }
    </script>
@endsection
