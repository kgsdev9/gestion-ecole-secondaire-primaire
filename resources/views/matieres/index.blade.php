@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="matiereSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES MATIERES
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterMatiere">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        @click="showModal = true">
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
                                                <th class="min-w-125px">Libellé Matière</th>
                                                <th class="min-w-125px">Description</th>
                                                <th class="min-w-125px">Date de création</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="matiere in paginatedMatieres" :key="matiere.id">
                                                <tr>
                                                    <td x-text="matiere.name"></td>
                                                    <td x-text="matiere.description"></td>
                                                    <td x-text="new Date(matiere.created_at).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td class="text-end">
                                                        <button @click="openModal(matiere)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteMatiere(matiere.id)"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour la création et la modification -->
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
                                    <label for="name" class="form-label">Libellé Matière</label>
                                    <input type="text" id="name" class="form-control" x-model="formData.name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" class="form-control" x-model="formData.description" required></textarea>
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
        function matiereSearch() {
            return {
                searchTerm: '',
                matieres: @json($matieres),
                filteredMatieres: [],
                currentPage: 1,
                matieresPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    name: '',
                    description: '',
                },
                currentMatiere: null,

                hideModal() {
                    this.showModal = false;
                    this.currentMatiere = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(matiere = null) {
                    this.isEdite = matiere !== null;
                    if (this.isEdite) {
                        this.currentMatiere = {
                            ...matiere
                        };
                        this.formData = {
                            name: this.currentMatiere.name,
                            description: this.currentMatiere.description,
                        };
                    } else {
                        this.resetForm();
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        name: '',
                        description: ''
                    };
                },

                async submitForm() {
                    this.isLoading = true;

                    if (!this.formData.name.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom est requis.',
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', this.formData.name);
                    formData.append('description', this.formData.description);
                    formData.append('matiere_id', this.currentMatiere ? this.currentMatiere.id : null);

                    try {
                        const response = await fetch('{{ route('matieres.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const matiere = data.matiere;

                            if (matiere) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Matière enregistrée avec succès.',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.matieres.findIndex(m => m.id === matiere.id);
                                    if (index !== -1) {
                                        this.matieres[index] = matiere;
                                    }
                                } else {
                                    this.matieres.push(matiere);
                                    this.matieres.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }

                                this.filterMatiere();
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

                deleteMatiere(id) {
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: 'Cela supprimera définitivement cette matière.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Non',
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/matieres/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                });

                                if (response.ok) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Matière supprimée avec succès.',
                                    });

                                    this.matieres = this.matieres.filter(matiere => matiere.id !== id);
                                    this.filterMatiere();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur lors de la suppression.',
                                    });
                                }
                            } catch (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur serveur.',
                                });
                            }
                        }
                    });
                },

                filterMatiere() {
                    this.filteredMatieres = this.matieres.filter(matiere => {
                        return matiere.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            matiere.description.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                },

                get paginatedMatieres() {
                    let start = (this.currentPage - 1) * this.matieresPerPage;
                    let end = start + this.matieresPerPage;
                    return this.filteredMatieres.slice(start, end);
                },

                init() {
                    this.filterMatiere();
                    this.isLoading = false;
                },
            };
        }
    </script>
@endsection
