@extends('layouts.app')
@section('title', 'Inscriptions')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="inscriptionForm()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES INSCRIPTIONS
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterInscriptions">
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
                                                <th class="min-w-125px">Élève</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Niveau</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="inscription in paginatedInscriptions" :key="inscription.id">
                                                <tr>
                                                    <td x-text="inscription.eleve.nom + ' ' + inscription.eleve.matricule"></td>
                                                    <td x-text="inscription.classe.name"></td>
                                                    <td x-text="inscription.niveau.name"></td>
                                                    <td x-text="inscription.annee_academique.name"></td>
                                                    <td class="text-end">
                                                        <button @click="viewInscription(inscription)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
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
                                                <button class="page-link"
                                                    @click="goToPage(currentPage - 1)">Précédent</button>
                                            </li>
                                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                                <button class="page-link"
                                                    @click="goToPage(currentPage + 1)">Suivant</button>
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

        <template x-if="showModal">
            <div class="modal fade show d-block" tabindex="-1" aria-modal="true" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Détails de l'inscription</h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Élève : </strong><span x-text="currentInscription.eleve.name"></span></p>
                            <p><strong>Classe : </strong><span x-text="currentInscription.classe.name"></span></p>
                            <p><strong>Niveau : </strong><span x-text="currentInscription.niveau.name"></span></p>
                            <p><strong>Année académique : </strong><span x-text="currentInscription.annee_academique.name"></span></p>
                            <p><strong>Date d'inscription : </strong><span x-text="currentInscription.date_inscription"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function inscriptionForm() {
            return {
                searchTerm: '',
                inscriptions: @json($inscriptions),
                filteredInscriptions: [],
                currentPage: 1,
                inscriptionsPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                currentInscription: null,

                hideModal() {
                    this.showModal = false;
                    this.currentInscription = null;
                },

                viewInscription(inscription) {
                    this.currentInscription = inscription;
                    this.showModal = true;
                },

                get paginatedInscriptions() {
                    return this.filteredInscriptions.slice((this.currentPage - 1) * this.inscriptionsPerPage, this.currentPage * this.inscriptionsPerPage);
                },

                filterInscriptions() {
                    this.filteredInscriptions = this.inscriptions.filter(inscription => {
                        return inscription.eleve.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.classe.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.niveau.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                    this.totalPages = Math.ceil(this.filteredInscriptions.length / this.inscriptionsPerPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                init() {
                    this.filteredInscriptions = this.inscriptions;
                    this.totalPages = Math.ceil(this.filteredInscriptions.length / this.inscriptionsPerPage);
                },
            };
        }
    </script>
@endsection
