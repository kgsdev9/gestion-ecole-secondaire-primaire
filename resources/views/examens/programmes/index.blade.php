@extends('layouts.app')
@section('title', 'Liste des programmes d\'examens')

@section('content')
<div class="app-main flex-column flex-row-fluid mt-4" x-data="programmeExamenSearch()" x-init="init()">
    <div class="d-flex flex-column flex-column-fluid">
        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        GESTION DES PROGRAMMES DES EXAMENS
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
                                    placeholder="Rechercher" x-model="searchTerm" @input="filterProgramme">
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
                                            <th class="min-w-125px">Code</th>
                                            <th class="min-w-125px">Titre</th>
                                            <th class="min-w-125px">Nom Examen</th>
                                            <th class="min-w-125px">Type Examen</th>
                                            <th class="min-w-125px">Classe</th>
                                            <th class="min-w-125px">Année académique</th>
                                            <th class="text-end min-w-100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        <template x-for="programme in paginatedProgrammes" :key="programme.id">
                                            <tr>
                                                <td x-text="programme.code"></td>
                                                <td x-text="programme.title"></td>
                                                <td x-text="programme.examen?.name ?? '-'"></td>
                                                <td x-text="programme.examen?.type_examen?.name ?? '-'"></td>
                                                <td x-text="programme.examen?.classe?.name ?? '-'"></td>
                                                <td x-text="programme.annee_academique?.name ?? '-'"></td>

                                                <td class="text-end">
                                                    <!-- Bouton Voir le programme (toujours visible) -->
                                                    <a
                                                        :href="`{{ route('examens.programme.show', ['programme' => '__ID__']) }}`.replace('__ID__', programme.examen?.id)"
                                                        class="btn btn-light btn-sm"
                                                        title="Voir le programme"
                                                    >
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    &nbsp;

                                                    <!-- Bouton Créer programme (visible uniquement si l'examen n'est pas clôturé) -->
                                                    <template x-if="programme.examen?.cloture != 1">
                                                        <a
                                                            :href="`{{ route('examens.programme.examens.create', ['id' => '__ID__']) }}`.replace('__ID__', programme.examen?.id)"
                                                            class="btn btn-warning btn-sm"
                                                            title="Créer un programme"
                                                        >
                                                            <i class="fa fa-calendar-check"></i>
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
    function programmeExamenSearch() {
        return {
            searchTerm: '',
            programmes: @json($programmesexamens),
            filteredProgrammes: [],
            currentPage: 1,
            programmesPerPage: 10,
            isLoading: false,

            filterProgramme() {
                this.filteredProgrammes = this.programmes.filter(p =>
                    (p.code ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (p.title ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (p.examen?.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase())
                );
            },

            get paginatedProgrammes() {
                let start = (this.currentPage - 1) * this.programmesPerPage;
                return this.filteredProgrammes.slice(start, start + this.programmesPerPage);
            },

            init() {
                this.filterProgramme();
            },
        }
    }
</script>
@endsection
