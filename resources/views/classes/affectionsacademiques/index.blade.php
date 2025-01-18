@extends('layouts.app')
@section('title', 'Salle de classe')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="classeForm()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES CLASSES
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterClasses">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
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
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Niveau</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="min-w-125px">Salle</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="classe in paginatedClasses" :key="classe.id">
                                                <tr>
                                                    <td x-text="classe.classe.name"></td>
                                                    <td x-text="classe.niveau.name"></td>
                                                    <td x-text="classe.annee_academique.name"></td>
                                                    <td x-text="classe.salle.name"></td>
                                                    <td class="text-end">
                                                        <button @click="openModal(classe)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteClasse(classe.id)"
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
                                            <option value="">Choisir une classe</option>
                                            @foreach ($classe as $classeroom)
                                                <option value="{{ $classeroom->id }}">{{ $classeroom->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="salle" class="form-label">Salle</label>
                                        <select id="salle_id" x-model="formData.salle_id" class="form-select" required>
                                            <option value="">Choisir une Salle</option>
                                            @foreach ($salles as $salle)
                                                <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="annee_academique" class="form-label">Année académique</label>
                                        <select id="annee_academique_id" x-model="formData.annee_academique_id"
                                            class="form-select" required>
                                            <option value="">Choisir une Année</option>
                                            @foreach ($anneesAcademiques as $annee)
                                                <option value="{{ $annee->id }}">{{ $annee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"
                                            x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>
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
        function classeForm() {
            return {
                searchTerm: '',
                classes: @json($classes),
                filteredClasses: [],
                currentPage: 1,
                classesPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    niveau_id: '',
                    classe_id: '',
                    salle_id: '',
                    annee_academique_id: '',
                },
                currentClasse: null,

                hideModal() {
                    this.showModal = false;
                    this.currentClasse = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(classe = null) {
                    this.isEdite = classe !== null;
                    if (this.isEdite) {
                        this.currentClasse = {
                            ...classe
                        };
                        this.formData = {
                            niveau_id: this.currentClasse.niveau_id,
                            salle_id: this.currentClasse.salle_id,
                            classe_id: this.currentClasse.classe_id,
                            annee_academique_id: this.currentClasse.annee_academique_id,
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
                        salle_id: '',
                        annee_academique_id: '',
                    };
                },

                async submitForm() {
                    if (!this.formData.niveau_id || !this.formData.classe_id || !this.formData.salle_id || !this
                        .formData.annee_academique_id) {
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
                    formData.append('salle_id', this.formData.salle_id);
                    formData.append('annee_academique_id', this.formData.annee_academique_id);

                    if (this.currentClasse) {
                        formData.append('affectionacademique_id', this.currentClasse.id);
                    }

                    try {
                        const response = await fetch('{{ route('affectionacademique.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const classe = data.classe;

                            if (classe) {
                                Swal.fire({
                                    icon: 'success',
                                    title: this.isEdite ? 'Classe mise à jour avec succès !' :
                                        'Classe créée avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.classes.findIndex(e => e.id === classe.id);
                                    if (index !== -1) {
                                        this.classes[index] = classe;
                                    }
                                } else {
                                    this.classes.push(classe);
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

                get paginatedClasses() {
                    return this.filteredClasses.slice((this.currentPage - 1) * this.classesPerPage, this.currentPage *
                        this.classesPerPage);
                },

                filterClasses() {
                    this.filteredClasses = this.classes.filter(classe => {
                        return classe.classe.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            classe.niveau.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            classe.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                    this.totalPages = Math.ceil(this.filteredClasses.length / this.classesPerPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                deleteClasse(classeId) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')) {
                        fetch(`/classes/${classeId}`, {
                                method: 'DELETE'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message === 'Classe supprimée avec succès') {
                                    this.classes = this.classes.filter(classe => classe.id !== classeId);
                                }
                            })
                            .catch(error => {
                                console.error("Erreur:", error);
                            });
                    }
                },

                init() {
                    this.filteredClasses = this.classes;
                    this.totalPages = Math.ceil(this.filteredClasses.length / this.classesPerPage);
                },
            };
        }
    </script>
@endsection
