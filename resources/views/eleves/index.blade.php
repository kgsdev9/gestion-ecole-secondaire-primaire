@extends('layouts.app')
@section('title', 'Liste des eleves')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="userSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES ELEVES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Eleves</li>
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

                                    <i class='fas fa-search  position-absolute ms-5'></i>

                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterUsers">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="printRapport" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportRaport" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
                                    </button>
                                    <button @click="showModal = true"
                                        class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
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
                                                <th class="min-w-125px">Nom</th>
                                                <th class="min-w-125px">Matricule</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Niveau</th>
                                                <th class="min-w-125px">Annéee Academique</th>
                                                <th class="min-w-125px">Date </th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="eleve in paginatedEleves" :key="eleve.id">
                                                <tr>
                                                    <td x-text="eleve.nom + ' ' + eleve.prenom"></td>
                                                    <td x-text="eleve.matricule"></td>
                                                    <td x-text="eleve.classe.name"></td>
                                                    <td x-text="eleve.niveau.name"></td>
                                                    <td x-text="eleve.anneeacademique.name"></td>
                                                    <td x-text="new Date(eleve.created_at).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td class="text-end">
                                                        <button @click="openModal(eleve)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        <button @click="deleteEleve(eleve.id)"
                                                            class="btn btn-danger btn-sm mx-2">
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
                                                    @click="goToPage(currentPage - 1)">Précedent</button>
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
                                    <!-- Nom -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" id="nom" class="form-control" x-model="formData.nom"
                                            required>
                                    </div>

                                    <!-- Prénom -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" id="prenom" class="form-control"
                                            x-model="formData.prenom" required>
                                    </div>

                                    <!-- Photo -->
                                    <div class="col-md-6 mb-3">
                                        <label for="photo" class="form-label">Photo</label>
                                        <input type="file" id="photo" class="form-control"
                                            x-model="formData.photo">
                                    </div>

                                    <!-- Matricule -->
                                    <div class="col-md-6 mb-3">
                                        <label for="matricule" class="form-label">Matricule</label>
                                        <input type="text" id="matricule" class="form-control"
                                            x-model="formData.matricule" required>
                                    </div>

                                    <!-- Classe -->
                                    <div class="col-md-6 mb-3">
                                        <label for="classe_id" class="form-label">Classe</label>
                                        <select id="classe_id" x-model="formData.classe_id" class="form-select" required>
                                            <option value="">Choisir une Classe</option>
                                            @foreach ($listeclasse as $classe)
                                                <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Année académique -->
                                    <div class="col-md-6 mb-3">
                                        <label for="annee_academique_id" class="form-label">Année Académique</label>
                                        <select id="annee_academique_id" x-model="formData.annee_academique_id"
                                            class="form-select" required>
                                            <option value="">Choisir une Année Académique</option>
                                            @foreach ($listeannee as $annee)
                                                <option value="{{ $annee->id }}">{{ $annee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Niveau -->
                                    <div class="col-md-6 mb-3">
                                        <label for="niveau_id" class="form-label">Niveau</label>
                                        <select id="niveau_id" x-model="formData.niveau_id" class="form-select" required>
                                            <option value="">Choisir un Niveau</option>
                                            @foreach ($listeniveaux as $niveau)
                                                <option value="{{ $niveau->id }}">{{ $niveau->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date de naissance -->
                                    <div class="col-md-6 mb-3">
                                        <label for="date_naissance" class="form-label">Date de Naissance</label>
                                        <input type="date" id="date_naissance" class="form-control"
                                            x-model="formData.date_naissance">
                                    </div>

                                    <!-- Adresse -->
                                    <div class="col-md-6 mb-3">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <input type="text" id="adresse" class="form-control"
                                            x-model="formData.adresse">
                                    </div>

                                    <!-- Téléphone Parent -->
                                    <div class="col-md-6 mb-3">
                                        <label for="telephone_parant" class="form-label">Téléphone Parent</label>
                                        <input type="text" id="telephone_parant" class="form-control"
                                            x-model="formData.telephone_parant">
                                    </div>

                                    <!-- Genre -->
                                    <div class="col-md-6 mb-3">
                                        <label for="genre_id" class="form-label">Genre</label>
                                        <select id="genre_id" x-model="formData.genre_id" class="form-select" required>
                                            <option value="">Choisir un Niveau</option>
                                            @foreach ($genres as $genre)
                                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Statut Élève -->
                                    <div class="col-md-6 mb-3">
                                        <label for="statuseleve_id" class="form-label">Statut Élève</label>
                                        <select id="statuseleve_id" class="form-select" x-model="formData.statuseleve_id"
                                            required>
                                            <option value="">Choisir un statut</option>
                                            @foreach ($statuseleves as $statuseleve)
                                                <option value="{{ $statuseleve->id }}">{{ $statuseleve->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nationalité -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nationalite" class="form-label">Nationalité</label>
                                        <input type="text" id="nationalite" class="form-control"
                                            x-model="formData.nationalite" required>
                                    </div>


                                    <div class="col-md-6 mb-3 mt-8">
                                        <label for="submit" class="form-label"></label>
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
        function userSearch() {
            return {
                searchTerm: '',
                eleves: @json($eleves),
                genres: @json($genres),
                statuseleves: @json($statuseleves),
                filteredEleves: [],
                currentPage: 1,
                elevesPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdite: false,
                formData: {
                    nom: '',
                    prenom: '',
                    photo: '',
                    matricule: '',
                    genre_id: '',
                    statuseleve_id: '',
                    nationalite: '',
                    classe_id: '',
                    annee_academique_id: '',
                    niveau_id: '',
                    date_naissance: '',
                    adresse: '',
                    telephone_parant: '',
                },

                currentEleve: null,

                hideModal() {
                    this.showModal = false;
                    this.currentEleve = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(eleve = null) {
                    this.isEdite = eleve !== null;

                    if (this.isEdite) {
                        this.currentEleve = {
                            ...eleve
                        };

                        this.formData = {
                            nom: this.currentEleve.nom,
                            prenom: this.currentEleve.prenom,
                            photo: this.currentEleve.photo,
                            matricule: this.currentEleve.matricule,
                            classe_id: this.currentEleve.classe_id,
                            annee_academique_id: this.currentEleve.anneeacademique_id,
                            niveau_id: this.currentEleve.niveau_id,
                            date_naissance: this.currentEleve.date_naissance,
                            adresse: this.currentEleve.adresse,
                            telephone_parant: this.currentEleve.telephone_parent,
                            statuseleve_id: this.currentEleve.statuseleve_id,
                            genre_id: this.currentEleve.genre_id,
                            nationalite: this.currentEleve.nationalite,
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
                        photo: '',
                        matricule: '',
                        classe_id: '',
                        annee_academique_id: '',
                        niveau_id: '',
                        date_naissance: '',
                        adresse: '',
                        telephone_parant: '',
                        statuseleve_id: '',
                        genre_id: '',
                        nationalite: '',
                    };


                },

                async deleteClient(clientId) {
                    try {
                        const url =
                            `{{ route('eleves.destroy', ['elefe' => '__ID__']) }}`.replace(
                                "__ID__",
                                clientId
                            );

                        const response = await fetch(url, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });


                                this.users = this.users.filter(client => client.id !== clientId);


                                this.filterUsers();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: result.message,
                                    showConfirmButton: true,
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erreur lors de la requête.",
                                showConfirmButton: true,
                            });
                        }
                    } catch (error) {
                        console.error("Erreur réseau :", error);
                        Swal.fire({
                            icon: "error",
                            title: "Une erreur réseau s'est produite.",
                            showConfirmButton: true,
                        });
                    }
                },

                async submitForm() {
                    this.isLoading = true;

                    // Validation des champs obligatoires
                    if (!this.formData.nom || this.formData.nom.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.prenom || this.formData.prenom.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le prénom est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.classe_id || this.formData.classe_id === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'La classe est requise.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.annee_academique_id || this.formData.annee_academique_id === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'L\'année académique est requise.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.niveau_id || this.formData.niveau_id === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le niveau est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.date_naissance || this.formData.date_naissance.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'La date de naissance est requise.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.adresse || this.formData.adresse.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'L\'adresse est requise.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    if (!this.formData.genre_id || this.formData.genre_id === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le genre est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.statuseleve_id || this.formData.statuseleve_id === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le status de l\'eleve est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.nationalite || this.formData.nationalite.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'La nationalite  est requise.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    if (!this.formData.telephone_parant || this.formData.telephone_parant.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le téléphone du parent est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('nom', this.formData.nom);
                    formData.append('prenom', this.formData.prenom);
                    formData.append('classe_id', this.formData.classe_id);
                    formData.append('annee_academique_id', this.formData.annee_academique_id);
                    formData.append('niveau_id', this.formData.niveau_id);
                    formData.append('date_naissance', this.formData.date_naissance);
                    formData.append('adresse', this.formData.adresse);
                    formData.append('telephone_parant', this.formData.telephone_parant);
                    formData.append('statuseleve_id', this.formData.statuseleve_id);
                    formData.append('genre_id', this.formData.genre_id);
                    formData.append('nationalite', this.formData.nationalite);

                    if (this.currentEleve) {
                        formData.append('eleve_id', this.currentEleve.id);
                    }

                    try {
                        const response = await fetch('{{ route('eleves.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const eleve = data.eleve;

                            if (eleve) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Élève enregistré avec succès!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                if (this.isEdite) {
                                    const index = this.eleves.findIndex(e => e.id === eleve.id);
                                    if (index !== -1) this.eleves[index] = eleve;
                                } else {
                                    this.eleves.push(eleve);
                                    this.eleves.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }

                                this.filterEleves();
                                this.resetForm();
                                this.hideModal();
                            }
                        } else {
                            if (response.status === 409) {
                                const errorData = await response.json();
                                Swal.fire({
                                    icon: 'error',
                                    title: errorData.message ||
                                        'Cet élève est déjà inscrit avec cette configuration.',
                                    showConfirmButton: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur lors de l\'enregistrement.',
                                    showConfirmButton: true
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Erreur réseau :', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Une erreur est survenue.',
                            showConfirmButton: true
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },



                get paginatedEleves() {
                    let start = (this.currentPage - 1) * this.elevesPerPage;
                    let end = start + this.elevesPerPage;
                    return this.filteredEleves.slice(start, end);
                },

                filterEleves() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredEleves = this.eleves.filter(eleve => {
                        return eleve.nom && eleve.nom.toLowerCase().includes(term) ||
                            eleve.telephone_parant && eleve.telephone_parant.toLowerCase().includes(term);
                    });
                    this.totalPages = Math.ceil(this.filteredEleves.length / this.elevesPerPage);
                    this.currentPage = 1;
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                init() {
                    this.filterEleves();
                    this.isLoading = false;
                }
            };
        }
    </script>
@endsection
