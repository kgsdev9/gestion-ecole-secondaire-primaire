@extends('layouts.app')
@section('title', 'Année académique')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="anneeAcademiqueManagement()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES ANNÉES ACADÉMIQUES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Année Académique</li>
                        </ul>
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterAnneeAcademiques">
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="printAnneeAcademiques" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportAnneeAcademiques" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
                                    </button>
                                    <button @click="openModal()" class="btn btn-light btn-active-light-primary btn-sm">
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
                                                <th class="min-w-125px">Libellé Année</th>
                                                <th class="min-w-125px">Date début</th>
                                                <th class="min-w-125px">Date Fin</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="annee in paginatedAnneeAcademiques" :key="annee.id">
                                                <tr>
                                                    <td x-text="annee.name"></td>
                                                    <td x-text="formatDate(annee.date_debut)"></td>
                                                    <td x-text="formatDate(annee.date_fin)"></td>
                                                    <td class="text-end">
                                                        <button @click="openModal(annee)"
                                                            class="btn btn-primary ms-2 btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteAnneeAcademique(annee.id)"
                                                            class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>

                                                        <a :href="`{{ route('gestion.semestre', ['id' => '__ID__']) }}`.replace(
                                                            '__ID__', annee.id)"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-cogs"></i> Configurer les semestres
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

            <!-- MODAL -->
            <template x-if="showModal">
                <div class="modal fade show d-block" tabindex="-1" aria-modal="true"
                    style="background-color: rgba(0,0,0,0.5)">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" x-text="isEdit ? 'Modification' : 'Création'"></h5>
                                <button type="button" class="btn-close" @click="closeModal()"></button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="submitForm">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Libellé Année Académique</label>
                                        <input type="text" id="name" class="form-control" x-model="formData.name"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_debut" class="form-label">Date début</label>
                                        <input type="date" id="date_debut" class="form-control"
                                            x-model="formData.date_debut" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_fin" class="form-label">Date fin</label>
                                        <input type="date" id="date_fin" class="form-control"
                                            x-model="formData.date_fin" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                        x-text="isEdit ? 'Mettre à jour' : 'Enregistrer'"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <script>
            function anneeAcademiqueManagement() {
                return {
                    searchTerm: '',
                    anneeAcademiques: @json($listeanneeacademique),
                    filteredAnneeAcademiques: [],
                    currentPage: 1,
                    anneeAcademiquesPerPage: 10,
                    totalPages: 0,
                    isLoading: true,
                    showModal: false,
                    isEdit: false,
                    formData: {
                        name: '',
                        date_debut: '',
                        date_fin: ''
                    },
                    currentAnneeAcademique: null,

                    init() {
                        this.filterAnneeAcademiques();
                        this.isLoading = false;
                    },

                    openModal(anneeAcademique = null) {
                        this.isEdit = !!anneeAcademique;
                        if (this.isEdit) {
                            this.currentAnneeAcademique = {
                                ...anneeAcademique
                            };
                            this.formData.name = anneeAcademique.name;
                            this.formData.date_debut = anneeAcademique.date_debut;
                            this.formData.date_fin = anneeAcademique.date_fin;
                        } else {
                            this.resetForm();
                        }
                        this.showModal = true;
                    },

                    closeModal() {
                        this.showModal = false;
                        this.resetForm();
                    },

                    resetForm() {
                        this.formData = {
                            name: '',
                            date_debut: '',
                            date_fin: ''
                        };
                        this.isEdit = false;
                        this.currentAnneeAcademique = null;
                    },

                    filterAnneeAcademiques() {
                        const term = this.searchTerm.toLowerCase();
                        this.filteredAnneeAcademiques = this.anneeAcademiques.filter(annee =>
                            annee.name.toLowerCase().includes(term)
                        );
                        this.totalPages = Math.ceil(this.filteredAnneeAcademiques.length / this.anneeAcademiquesPerPage);
                        this.currentPage = 1;
                    },

                    get paginatedAnneeAcademiques() {
                        const start = (this.currentPage - 1) * this.anneeAcademiquesPerPage;
                        return this.filteredAnneeAcademiques.slice(start, start + this.anneeAcademiquesPerPage);
                    },

                    formatDate(date) {
                        return new Date(date).toLocaleDateString('fr-FR');
                    },

                    async submitForm() {
                        this.isLoading = true;

                        if (!this.formData.name || this.formData.name.trim() === '') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Le libellé est requis.',
                                showConfirmButton: true
                            });
                            this.isLoading = false;
                            return;
                        }

                        const debut = new Date(this.formData.date_debut);
                        const fin = new Date(this.formData.date_fin);

                        // ✅ Vérification des dates
                        if (debut >= fin) {
                            Swal.fire({
                                icon: 'error',
                                title: 'La date de début doit être inférieure à la date de fin.',
                            });
                            this.isLoading = false;
                            return;
                        }

                        const formData = new FormData();
                        formData.append('name', this.formData.name);
                        formData.append('date_debut', this.formData.date_debut);
                        formData.append('date_fin', this.formData.date_fin);
                        if (this.isEdit) {
                            formData.append('annee_academique_id', this.currentAnneeAcademique.id);
                        }

                        try {
                            const response = await fetch('{{ route('anneeacademique.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                const anneeAcademique = data.anneeAcademique;

                                if (anneeAcademique) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sauvegarde réussie !',
                                        timer: 1500
                                    });

                                    if (this.isEdit) {
                                        const index = this.anneeAcademiques.findIndex(p => p.id === anneeAcademique.id);
                                        if (index !== -1) {
                                            this.anneeAcademiques[index] = anneeAcademique;
                                        }
                                    } else {
                                        this.anneeAcademiques.unshift(anneeAcademique);
                                    }

                                    this.filterAnneeAcademiques();
                                    this.resetForm();
                                    this.closeModal();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur : données invalides.'
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur lors de l\'enregistrement.'
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur réseau.'
                            });
                        } finally {
                            this.isLoading = false;
                        }
                    },


                    async deleteAnneeAcademique(id) {
                        try {
                            const url = `{{ route('anneeacademique.destroy', ['anneeacademique' => '__ID__']) }}`.replace(
                                "__ID__", id);

                            const response = await fetch(url, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                            });

                            if (response.ok) {
                                const result = await response.json();
                                if (result.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: result.message,
                                        timer: 1500
                                    });
                                    this.anneeAcademiques = this.anneeAcademiques.filter(annee => annee.id !== id);
                                    this.filterAnneeAcademiques();
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: result.message
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Erreur serveur."
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: "error",
                                title: "Erreur réseau."
                            });
                        }
                    },
                };
            }
        </script>
    </div>
@endsection
