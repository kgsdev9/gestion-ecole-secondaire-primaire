@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="enseignantForm()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES ENSEIGNANTS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Enseignants</li>
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterEnseignants">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="printEnseignants" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportEnseignants" class="btn btn-light-primary btn-sm">
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
                                                <th class="min-w-125px">Nom</th>
                                                <th class="min-w-125px">Prénom</th>
                                                <th class="min-w-125px">Matricule</th>
                                                <th class="min-w-125px">Email</th>
                                                <th class="min-w-125px">Matiere</th>
                                                <th class="min-w-125px">Date de création</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="enseignant in paginatedEnseignants" :key="enseignant.id">
                                                <tr>
                                                    <td x-text="enseignant.nom"></td>
                                                    <td x-text="enseignant.prenom"></td>
                                                    <td x-text="enseignant.matricule"></td>
                                                    <td x-text="enseignant.email"></td>
                                                    <td x-text="enseignant.matiere.name"></td>
                                                    <td
                                                        x-text="new Date(enseignant.created_at).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td class="text-end">
                                                        <button @click="openModal(enseignant)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        <button @click="deleteEnseignant(enseignant.id)"
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
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" id="nom" class="form-control" x-model="formData.nom"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" id="prenom" class="form-control" x-model="formData.prenom"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="matricule" class="form-label">Matricule</label>
                                    <input type="text" id="matricule" class="form-control"
                                        x-model="formData.matricule" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" x-model="formData.email"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" id="adresse" class="form-control" x-model="formData.adresse"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" id="telephone" class="form-control"
                                        x-model="formData.telephone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="matiere" class="form-label">Matière</label>
                                    <select id="matiere" class="form-control" x-model="formData.matiere_id" required>
                                        <option value="">Sélectionnez une matière</option>
                                        <template x-for="matiere in matieres" :key="matiere.id">
                                            <option :value="matiere.id" x-text="matiere.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" id="photo" class="form-control" @change="handleFileUpload"
                                        :disabled="isEdite">
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
        function enseignantForm() {
            return {
                searchTerm: '',
                matieres: @json($matieres),
                enseignants: @json($enseignants),
                filteredEnseignants: [],
                currentPage: 1,
                enseignantsPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    nom: '',
                    prenom: '',
                    matricule: '',
                    email: '',
                    adresse: '',
                    telephone: '',
                    matiere_id: '',
                    photo: null, // Pour la photo
                },
                currentEnseignant: null,

                hideModal() {
                    this.showModal = false;
                    this.currentEnseignant = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(enseignant = null) {
                    this.isEdite = enseignant !== null;
                    if (this.isEdite) {
                        this.currentEnseignant = {
                            ...enseignant
                        };
                        this.formData = {
                            nom: this.currentEnseignant.nom,
                            prenom: this.currentEnseignant.prenom,
                            matricule: this.currentEnseignant.matricule,
                            email: this.currentEnseignant.email,
                            adresse: this.currentEnseignant.adresse,
                            telephone: this.currentEnseignant.telephone,
                            matiere_id: this.currentEnseignant.matiere_id,
                        };
                    } else {
                        this.resetForm();
                        this.isEdite = false;
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        nom: '',
                        prenom: '',
                        matricule: '',
                        email: '',
                        adresse: '',
                        telephone: '',
                        matiere_id: '',
                        photo: null,
                    };
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.formData.photo = file;
                    }
                },

                async submitForm() {
                    if (!this.formData.nom || !this.formData.prenom || !this.formData.matricule || !this.formData
                        .email || !this.formData.adresse || !this.formData.telephone || !this.formData.matiere_id) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tous les champs sont requis.',
                            showConfirmButton: true,
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('nom', this.formData.nom);
                    formData.append('prenom', this.formData.prenom);
                    formData.append('matricule', this.formData.matricule);
                    formData.append('email', this.formData.email);
                    formData.append('adresse', this.formData.adresse);
                    formData.append('telephone', this.formData.telephone);
                    formData.append('matiere_id', this.formData.matiere_id);
                    if (this.formData.photo) {
                        formData.append('photo', this.formData.photo); // Ajouter la photo si présente
                    }

                    try {
                        const response = await fetch(this.isEdite ?
                            `{{ route('enseignants.update', ['enseignant' => '__ID__']) }}`.replace('__ID__', this
                                .currentEnseignant.id) : '{{ route('enseignants.store') }}', {
                                method: this.isEdite ? 'PUT' : 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: formData,
                            });

                        if (response.ok) {
                            const data = await response.json();
                            const enseignant = data.enseignant;

                            if (enseignant) {
                                Swal.fire({
                                    icon: 'success',
                                    title: this.isEdite ? 'Enseignant mis à jour avec succès !' :
                                        'Enseignant créé avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.enseignants.findIndex(e => e.id === enseignant.id);
                                    if (index !== -1) {
                                        this.enseignants[index] = enseignant;
                                    }
                                } else {
                                    this.enseignants.push(enseignant);
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

                // Fonction de pagination
                get paginatedEnseignants() {
                    return this.filteredEnseignants.slice((this.currentPage - 1) * this.enseignantsPerPage, this
                        .currentPage * this.enseignantsPerPage);
                },

                filterEnseignants() {
                    this.filteredEnseignants = this.enseignants.filter(enseignant => {
                        return enseignant.nom.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            enseignant.prenom.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            enseignant.matricule.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            enseignant.email.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                    this.totalPages = Math.ceil(this.filteredEnseignants.length / this.enseignantsPerPage);
                },

                goToPage(page) {
                    if (page > 0 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                init() {

                    this.filteredEnseignants = this.enseignants;
                    this.totalPages = Math.ceil(this.filteredEnseignants.length / this.enseignantsPerPage);
                },


            };
        }
    </script>
@endsection
