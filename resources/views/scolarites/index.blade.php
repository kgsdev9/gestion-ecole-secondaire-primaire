@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="scolariteForm()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES SCOLARITES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Scolarites</li>
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
                                           placeholder="Rechercher" x-model="searchTerm" @input="filterScolarites">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="printScolarites" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportScolarites" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Exporter
                                    </button>

                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                            @click="showModal = true">
                                        <i class='fa fa-add'></i> Création
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
                                                <th class="min-w-125px">Niveau</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="min-w-125px">Montant</th>
                                                <th class="min-w-125px">Date de création</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="scolarite in paginatedScolarites" :key="scolarite.id">
                                                <tr>
                                                    <td x-text="scolarite.niveau.name"></td>
                                                    <td x-text="scolarite.classe.name"></td>
                                                    <td x-text="scolarite.annee_academique.name"></td>
                                                    <td x-text="scolarite.montant_scolarite"></td>
                                                    <td
                                                        x-text="new Date(scolarite.created_at).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td class="text-end">
                                                        <button @click="openModal(scolarite)"
                                                                class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        <button @click="deleteScolarite(scolarite.id)"
                                                                class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
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
                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="niveau" class="form-label">Niveau</label>
                                        <select id="niveau_id" x-model="formData.niveau_id" class="form-select" required>
                                            <option value="">Choisir un Niveau</option>
                                            @foreach ($niveaux as $niveau)
                                                <option value="{{ $niveau->id }}">{{ $niveau->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="classe" class="form-label">Classe</label>
                                        <select id="classe_id" x-model="formData.classe_id" class="form-select" required>
                                            <option value="">Choisir une Classe</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    

                                    salles
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="annee_academique" class="form-label">Année académique</label>
                                        <select id="annee_academique_id" x-model="formData.annee_academique_id" class="form-select" required>
                                            <option value="">Choisir une Année</option>
                                            @foreach ($anneesAcademiques as $annee)
                                                <option value="{{ $annee->id }}">{{ $annee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="montant_scolarite" class="form-label">Montant de la scolarité</label>
                                        <input type="number" id="montant_scolarite" class="form-control" x-model="formData.montant_scolarite" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <script>
        function scolariteForm() {
            return {
                searchTerm: '',
                scolarites: @json($scolarites),
                filteredScolarites: [],
                currentPage: 1,
                scolaritesPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    niveau_id: '',
                    classe_id: '',
                    annee_academique_id: '',
                    montant_scolarite: '',
                },
                currentScolarite: null,

                hideModal() {
                    this.showModal = false;
                    this.currentScolarite = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(scolarite = null) {
                    this.isEdite = scolarite !== null;
                    if (this.isEdite) {
                        this.currentScolarite = {
                            ...scolarite
                        };

                        this.formData = {
                            niveau_id: this.currentScolarite.niveau_id,
                            classe_id: this.currentScolarite.classe_id,
                            annee_academique_id: this.currentScolarite.annee_academique_id,
                            montant_scolarite: this.currentScolarite.montant_scolarite,
                        };
                    } else {
                        this.resetForm();
                        this.isEdite = false;
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        niveau_id: '',
                        classe_id: '',
                        annee_academique_id: '',
                        montant_scolarite: '',
                    };
                },

                async submitForm() {
                    if (!this.formData.niveau_id || !this.formData.classe_id || !this.formData.annee_academique_id || !this.formData.montant_scolarite) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tous les champs sont requis.',
                            showConfirmButton: true,
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('niveau_id', this.formData.niveau_id);
                    formData.append('classe_id', this.formData.classe_id);
                    formData.append('annee_academique_id', this.formData.annee_academique_id);
                    formData.append('montant_scolarite', this.formData.montant_scolarite);

                    if (this.currentScolarite) {
                        formData.append('scolarite_id', this.currentScolarite.id);
                    }

                    try {
                        const response = await fetch('{{ route('scolarites.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const scolarite = data.scolarite;

                            if (scolarite) {
                                Swal.fire({
                                    icon: 'success',
                                    title: this.isEdite ? 'Scolarité mise à jour avec succès !' :
                                        'Scolarité créée avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.scolarites.findIndex(e => e.id === scolarite.id);
                                    if (index !== -1) {
                                        this.scolarites[index] = scolarite;
                                    }
                                } else {
                                    this.scolarites.push(scolarite);
                                }

                                this.resetForm();
                                this.hideModal();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur lors de l\'enregistrement.',
                                    showConfirmButton: true,
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Une erreur s\'est produite.',
                                showConfirmButton: true,
                            });
                        }
                    } catch (error) {
                        console.error('Erreur réseau:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Une erreur est survenue.',
                            showConfirmButton: true,
                        });
                    }
                },

                get paginatedScolarites() {
                    return this.filteredScolarites.slice((this.currentPage - 1) * this.scolaritesPerPage, this
                        .currentPage * this.scolaritesPerPage);
                },

                filterScolarites() {
                    this.filteredScolarites = this.scolarites.filter(scolarite => {
                        return scolarite.montant_scolarite.toString().includes(this.searchTerm) ||
                            scolarite.niveau.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            scolarite.classe.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            scolarite.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                    this.totalPages = Math.ceil(this.filteredScolarites.length / this.scolaritesPerPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                deleteScolarite(scolariteId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette scolarité ?')) {
                        fetch(`/scolarites/${scolariteId}`, {
                                method: 'DELETE'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message === 'Scolarité supprimée avec succès') {
                                    this.scolarites = this.scolarites.filter(scolarite => scolarite.id !== scolariteId);
                                }
                            })
                            .catch(error => {
                                console.error("Erreur:", error);
                            });
                    }
                },

                init() {
                    this.filteredScolarites = this.scolarites;
                    this.totalPages = Math.ceil(this.filteredScolarites.length / this.scolaritesPerPage);
                },
            };
        }
    </script>
@endsection
