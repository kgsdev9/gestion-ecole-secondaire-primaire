@extends('layouts.app')
@section('title', 'Liste des moyennes d\'examens')

@section('content')
<div class="app-main flex-column flex-row-fluid mt-4" x-data="moyenneExamenSearch()" x-init="init()">
    <div class="d-flex flex-column flex-column-fluid">
        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                        GESTION DES MOYENNES D'EXAMENS
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
                                       placeholder="Rechercher"
                                       x-model="searchTerm"
                                       @input="filterMoyenne">
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
                                            <th>Code</th>
                                            <th>Titre</th>
                                            <th>Examen</th>
                                            <th>Type</th>
                                            <th>Classe</th>
                                            <th>Année</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        <template x-for="moyenne in paginatedMoyennes" :key="moyenne.id">
                                            <tr>
                                                <td x-text="moyenne.code"></td>
                                                <td x-text="moyenne.title"></td>
                                                <td x-text="moyenne.examen?.name ?? '-'"></td>
                                                <td x-text="moyenne.examen?.type_examen?.name ?? '-'"></td>
                                                <td x-text="moyenne.examen?.classe?.name ?? '-'"></td>
                                                <td x-text="moyenne.annee_academique?.name ?? '-'"></td>
                                                <td class="text-end">
                                                    <!-- Bouton Voir la moyenne (toujours visible) -->
                                                    <a
                                                        :href="`{{ route('examens.moyenne.show', ['moyenne' => '__ID__']) }}`.replace('__ID__', moyenne.examen.id)"
                                                        class="btn btn-light btn-sm"
                                                        title="Voir la moyenne"
                                                    >
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    &nbsp;

                                                    <!-- Bouton Modifier (visible uniquement si l'examen n'est pas clôturé) -->
                                                    <template x-if="moyenne.examen?.cloture != 1">
                                                        <a
                                                            :href="`{{ route('examens.moyenne.edit', ['moyenne' => '__ID__']) }}`.replace('__ID__', moyenne.examen.id)"
                                                            class="btn btn-light btn-sm"
                                                            title="Modifier"
                                                        >
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </template>

                                                    &nbsp;

                                                    <!-- Bouton Calculer la moyenne (visible uniquement si l'examen n'est pas clôturé) -->
                                                    <template x-if="moyenne.examen?.cloture != 1">
                                                        <a
                                                            :href="`{{ route('examens.moyenne.examens.create', ['id' => '__ID__']) }}`.replace('__ID__', moyenne.examen.id)"
                                                            class="btn btn-info btn-sm"
                                                            title="Calculer la moyenne"
                                                        >
                                                            <i class="fa fa-calculator"></i>
                                                        </a>
                                                    </template>
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
function moyenneExamenSearch() {
    return {
        searchTerm: '',
        moyennes: @json($moyennes),
        filteredMoyennes: [],
        currentPage: 1,
        moyennesPerPage: 10,
        isLoading: false,

        filterMoyenne() {
            this.filteredMoyennes = this.moyennes.filter(m =>
                (m.code ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (m.title ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (m.examen?.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },

        get paginatedMoyennes() {
            let start = (this.currentPage - 1) * this.moyennesPerPage;
            return this.filteredMoyennes.slice(start, start + this.moyennesPerPage);
        },

        init() {
            this.filterMoyenne();
        }
    }
}
</script>
@endsection
