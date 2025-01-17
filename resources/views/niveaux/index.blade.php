@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="niveauSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES NIVEAUX
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Niveaux</li>
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterNiveaux">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="showModal = true"
                                        class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
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
                                                <th class="min-w-125px">Libellé Niveau</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="niveau in paginatedNiveaux" :key="niveau.id">
                                                <tr>
                                                    <td x-text="niveau.name"></td>
                                                    <td class="text-end">
                                                        <button @click="openModal(niveau)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteNiveau(niveau.id)"
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Libellé Niveau</label>
                                    <input type="text" id="name" class="form-control" x-model="formData.name"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary"
                                    x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function niveauSearch() {
            return {
                searchTerm: '',
                niveaux: @json($niveaux),
                filteredNiveaux: [],
                currentPage: 1,
                niveauxPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdite: false,
                formData: {
                    name: '',
                },
                currentNiveau: null,

                hideModal() {
                    this.showModal = false;
                    this.currentNiveau = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(niveau = null) {
                    this.isEdite = niveau !== null;
                    if (this.isEdite) {
                        this.currentNiveau = {
                            ...niveau
                        };
                        this.formData = {
                            name: this.currentNiveau.name
                        };
                    } else {
                        this.resetForm();
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        name: ''
                    };
                },

                async submitForm() {
                    this.isLoading = true;

                    if (!this.formData.name.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom du niveau est requis.',
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', this.formData.name);
                    formData.append('niveau_id', this.currentNiveau ? this.currentNiveau.id : null);

                    try {
                        const response = await fetch('{{ route('niveaux.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const niveau = data.niveau;

                            if (niveau) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Niveau enregistré avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.niveaux.findIndex(n => n.id === niveau.id);
                                    if (index !== -1) {
                                        this.niveaux[index] = niveau;
                                    }
                                } else {
                                    this.niveaux.push(niveau);
                                    this.niveaux.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }

                                this.filterNiveaux();
                                this.resetForm();
                                this.hideModal();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de l\'enregistrement.',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur serveur.',
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },

                async deleteNiveau(niveauId) {
                    try {
                        const response = await fetch(`/niveaux/${niveauId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                this.niveaux = this.niveaux.filter(niveau => niveau.id !== niveauId);
                                this.filterNiveaux();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: result.message,
                                    showConfirmButton: true,
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de la requête.',
                                showConfirmButton: true,
                            });
                        }
                    } catch (error) {
                        console.error('Erreur réseau :', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Une erreur réseau s\'est produite.',
                            showConfirmButton: true,
                        });
                    }
                },

                get paginatedNiveaux() {
                    let start = (this.currentPage - 1) * this.niveauxPerPage;
                    let end = start + this.niveauxPerPage;
                    return this.filteredNiveaux.slice(start, end);
                },

                changePage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                filterNiveaux() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredNiveaux = this.niveaux.filter(niveau =>
                        niveau.name.toLowerCase().includes(term)
                    );
                    this.totalPages = Math.ceil(this.filteredNiveaux.length / this.niveauxPerPage);
                },

                init() {
                    this.filterNiveaux();
                    this.isLoading = false;
                },
            };
        }
    </script>
@endsection
