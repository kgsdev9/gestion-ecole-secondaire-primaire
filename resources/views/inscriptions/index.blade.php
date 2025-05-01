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
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select x-model="classe_id" @change="filterClasse"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les classes </option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name"></option>
                                            </template>
                                        </select>
                                    </div>



                                    <button @click="printListeClasse" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportProducts" class="btn btn-light-primary btn-sm">
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
                                                    <td x-text="inscription.eleve.nom + ' ' + inscription.eleve.matricule">
                                                    </td>
                                                    <td x-text="inscription.classe.name"></td>
                                                    <td x-text="inscription.niveau.name"></td>
                                                    <td x-text="inscription.annee_academique.name"></td>
                                                    <td class="text-end">
                                                        <a :href="`{{ route('administration.print.fiche.inscription', ['inscriptionId' => '__ID__']) }}`
                                                        .replace(
                                                            '__ID__', inscription.eleve.id)"
                                                            class="btn btn-light btn-sm"
                                                            title="Imprimer la fiche d'inscription">
                                                            <i class="fa fa-printA"></i>
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


    </div>

    <script>
        function inscriptionForm() {
            return {
                searchTerm: '',
                classe_id: '',
                inscriptions: @json($inscriptions),
                classes: @json($classes),
                filteredInscriptions: [],
                currentPage: 1,
                inscriptionsPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                currentInscription: null,

                get paginatedInscriptions() {
                    return this.filteredInscriptions.slice((this.currentPage - 1) * this.inscriptionsPerPage, this
                        .currentPage * this.inscriptionsPerPage);
                },

                filterInscriptions() {
                    this.filteredInscriptions = this.inscriptions.filter(inscription => {
                        return inscription.eleve.nom.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.classe.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.niveau.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            inscription.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                    this.totalPages = Math.ceil(this.filteredInscriptions.length / this.inscriptionsPerPage);
                },

                filterClasse() {
                    this.filteredInscriptions = this.inscriptions.filter(inscription => {
                        return (this.classe_id === '' || inscription.classe.id == this.classe_id);
                    });
                    this.totalPages = Math.ceil(this.filteredInscriptions.length / this.inscriptionsPerPage);
                    this.currentPage = 1;
                },


                printListeClasse() {
                    // Vérifier si toutes les valeurs sont renseignées
                    if (!this.classe_id) {
                        alert("Veuillez selectionner la classt svp.");
                        return;
                    }

                    const formData = new FormData();
                    formData.append('classe_id', this.classe_id);

                    fetch('{{ route('administration.impression.classe') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.url) {
                                window.open(data.url, '_blank');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message || "Impossible de générer le bulletin.",
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de l’envoi :', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur serveur ou réseau.',
                            });
                        });
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
