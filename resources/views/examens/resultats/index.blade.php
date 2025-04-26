@extends('layouts.app')
@section('title', 'Rapport Résultats Examens')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="resultatSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Rapport des Résultats d'Examens
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
                                        placeholder="Rechercher un résultat" x-model="searchTerm" @input="filterResultats">
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
                                                <th>Code Examen </th>
                                                <th>Examen</th>
                                                <th>Année Académique</th>
                                                <th>Taux de Réussite (%)</th>
                                                <th>Moyenne Examen</th>
                                                <th>Admis</th>
                                                <th>Participants</th>
                                                <th>Statut Publication</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="resultat in paginatedResultats" :key="resultat.id">
                                                <tr>
                                                    <td x-text="resultat.code"></td>
                                                    <td x-text="resultat.examen.name"></td>
                                                    <td x-text="resultat.annee_academique.name"></td>
                                                    <td x-text="resultat.taux_reussite"></td>
                                                    <td x-text="resultat.moyenne_examen"></td>
                                                    <td x-text="resultat.nb_admis"></td>
                                                    <td x-text="resultat.nb_total_participant"></td>
                                                    <td>
                                                        <span class="badge" :class="resultat.statut_publication ? 'badge-light-success' : 'badge-light-danger'"
                                                            x-text="resultat.statut_publication ? 'Publié' : 'Non Publié'">
                                                        </span>
                                                    </td>

                                                    <td class="text-end">
                                                        <td class="text-end">
                                                            <a :href="`{{ route('examens.resultats.show', ['resultat' => '__ID__']) }}`
                                                                .replace(
                                                                    '__ID__', resultat.code)"
                                                                    class="btn btn-warning btn-sm"  title="Créeer la repartition">
                                                                    <i class="fa fa-print me-1"></i> 
                                                                </a>
                                                                &nbsp; &nbsp;

                                                                <a :href="`{{ route('examens.resultats.show', ['resultat' => '__ID__']) }}`
                                                                    .replace(
                                                                        '__ID__', resultat.code)"
                                                                        class="btn btn-light btn-sm" title="Visualisation de la repartition">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                        </td>
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
        function resultatSearch() {
            return {
                searchTerm: '',
                resultats: @json($resultats),
                filteredResultats: [],
                currentPage: 1,
                resultatsPerPage: 10,
                isLoading: false,

                filterResultats() {
                    this.filteredResultats = this.resultats.filter(r => {
                        return r.code.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                               r.examen.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                               r.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                },

                get paginatedResultats() {
                    let start = (this.currentPage - 1) * this.resultatsPerPage;
                    let end = start + this.resultatsPerPage;
                    return this.filteredResultats.slice(start, end);
                },

                init() {
                    this.filterResultats();
                    this.isLoading = false;
                }
            }
        }
    </script>
@endsection
