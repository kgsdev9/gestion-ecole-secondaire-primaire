@extends('layouts.app')
@section('title', 'Liste des clients')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="userSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES CLIENTS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Clients</li>
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
                                                <th class="min-w-125px">Libelle</th>
                                                <th class="min-w-125px">Télephone</th>
                                                <th class="min-w-125px">Email </th>
                                                <th class="min-w-125px">Fax </th>
                                                <th class="min-w-125px">Date</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="client in paginatedUsers" :key="client.id">
                                                <tr>
                                                    <td x-text="client.libtiers">
                                                    </td>
                                                    <td x-text="client.telephone"></td>
                                                    <td x-text="client.email "></td>
                                                    <td x-text="client.fax "></td>
                                                    <td x-text="new Date(client.created_at).toLocaleDateString('fr-FR')">
                                                    </td>

                                                    <td class="text-end">
                                                        <button @click="openModal(client)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        <button @click="deleteClient(client.id)"
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

                                    <!-- Libellé tiers -->
                                    <div class="col-md-6 mb-3">
                                        <label for="libtiers" class="form-label">Libellé Tiers</label>
                                        <input type="text" id="libtiers" class="form-control"
                                            x-model="formData.libtiers" required>
                                    </div>

                                    <!-- Adresse postale -->
                                    <div class="col-md-6 mb-3">
                                        <label for="adressepostale" class="form-label">Adresse Postale</label>
                                        <input type="text" id="adressepostale" class="form-control"
                                            x-model="formData.adressepostale">
                                    </div>

                                    <!-- Adresse géographique -->
                                    <div class="col-md-6 mb-3">
                                        <label for="adressegeo" class="form-label">Adresse Géographique</label>
                                        <input type="text" id="adressegeo" class="form-control"
                                            x-model="formData.adressegeo">
                                    </div>

                                    <!-- Fax -->
                                    <div class="col-md-6 mb-3">
                                        <label for="fax" class="form-label">Fax</label>
                                        <input type="text" id="fax" class="form-control"
                                            x-model="formData.fax">
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control"
                                            x-model="formData.email" required>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="col-md-6 mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="text" id="telephone" class="form-control"
                                            x-model="formData.telephone">
                                    </div>

                                    <!-- Numéro de contribuable -->
                                    <div class="col-md-6 mb-3">
                                        <label for="numerocomtribuabe" class="form-label">Numéro de Contribuable</label>
                                        <input type="text" id="numerocomtribuabe" class="form-control"
                                            x-model="formData.numerocomtribuabe">
                                    </div>

                                    <!-- Numéro de compte -->
                                    <div class="col-md-6 mb-3">
                                        <label for="numerodecompte" class="form-label">Numéro de Compte</label>
                                        <input type="text" id="numerodecompte" class="form-control"
                                            x-model="formData.numerodecompte">
                                    </div>

                                    <!-- Capital -->
                                    <div class="col-md-6 mb-3">
                                        <label for="capital" class="form-label">Capital</label>
                                        <input type="text" id="capital" class="form-control"
                                            x-model="formData.capital">
                                    </div>

                                    <!-- Régime fiscal -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tregimefiscal_id" class="form-label">Régime Fiscal</label>
                                        <select id="tregimefiscal_id" x-model="formData.tregimefiscal_id"
                                            class="form-select">
                                            <option value="">Choisir un régime fiscal</option>
                                            @foreach ($listeregimesfiscaux as $fiscal)
                                                <option value="{{ $fiscal->id }}">{{ $fiscal->libelleregimefiscale }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Code devise -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tcodedevise_id" class="form-label">Code Devise</label>
                                        <select id="tcodedevise_id" x-model="formData.tcodedevise_id"
                                            class="form-select">
                                            <option value="">Choisir une devise</option>
                                            @foreach ($listecodedevises as $devise)
                                                <option value="{{ $devise->id }}">{{ $devise->libellecodedevise }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3 mt-8">
                                        <label for="tcodedevise_id" class="form-label"></label>
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
                users: @json($listeclients),
                filteredUsers: [],
                currentPage: 1,
                usersPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdite: false,
                formData: {
                    libtiers: '',
                    adressepostale: '',
                    adressegeo: '',
                    fax: '',
                    email: '',
                    telephone: '',
                    numerocomtribuabe: '',
                    numerodecompte: '',
                    capital: '',
                    tregimefiscal_id: '',
                    tcodedevise_id: '',
                },
                currentClient: null,

                hideModal() {
                    this.showModal = false;
                    this.currentClient = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(client = null) {
                    this.isEdite = client !== null;


                    if (this.isEdite) {
                        this.currentClient = {
                            ...client
                        };


                        this.formData = {
                            libtiers: this.currentClient.libtiers,
                            email: this.currentClient.email,
                            adressepostale: this.currentClient.adressepostale,
                            adressegeo: this.currentClient.adressegeo,
                            telephone: this.currentClient.telephone,
                            fax: this.currentClient.fax,
                            capital: this.currentClient.capital,
                            numerodecompte: this.currentClient.capital,
                            numerocomtribuabe: this.currentClient.numerocomtribuabe,
                            tregimefiscal_id: this.currentClient.tregimefiscal_id,
                            tcodedevise_id: this.currentClient.tcodedevise_id,
                        };


                    } else {
                        this.resetForm();
                        this.isEdite = false;
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        name: '',
                        email: '',
                        role_id: '',
                        password: ''
                    };
                },

                async deleteClient(clientId) {
                    try {
                        const url =
                            `{{ route('clients.destroy', ['client' => '__ID__']) }}`.replace(
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
                    if (!this.formData.libtiers || this.formData.libtiers.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le libelle est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('libtiers', this.formData.libtiers);
                    formData.append('adressepostale', this.formData.adressepostale);
                    formData.append('adressegeo', this.formData.adressegeo);
                    formData.append('fax', this.formData.fax);
                    formData.append('email', this.formData.email);
                    formData.append('telephone', this.formData.telephone);
                    formData.append('numerocomtribuabe', this.formData.numerocomtribuabe);
                    formData.append('numerodecompte', this.formData.numerodecompte);
                    formData.append('capital', this.formData.capital);
                    formData.append('tregimefiscal_id', this.formData.tregimefiscal_id);
                    formData.append('tcodedevise_id', this.formData.tcodedevise_id);
                    if (!this.currentClient) {
                        formData.append('client_id', null);
                    } else {
                        formData.append('client_id', this.currentClient.id);
                    }

                    // formData.append('client_id', this.currentClient.id ? this.currentClient.id : null);
                    try {


                        const response = await fetch('{{ route('clients.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const client = data.client;



                            if (client) {


                                Swal.fire({
                                    icon: 'success',
                                    title: 'Client enregistré avec succès!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                if (this.isEdite) {
                                    const index = this.users.findIndex(u => u.id === client.id);
                                    if (index !== -1) this.users[index] = client;
                                } else {
                                    this.users.push(client);

                                    this.users.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }


                                this.filterUsers();
                                this.resetForm();
                                this.hideModal();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de l\'enregistrement.',
                                showConfirmButton: true
                            });
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

                get paginatedUsers() {
                    let start = (this.currentPage - 1) * this.usersPerPage;
                    let end = start + this.usersPerPage;
                    return this.filteredUsers.slice(start, end);
                },

                filterUsers() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredUsers = this.users.filter(user => {
                        return user.libtiers && user.libtiers.toLowerCase().includes(term) || user.telephone &&

                            user.telephone.toLowerCase().includes(term);
                    });
                    this.totalPages = Math.ceil(this.filteredUsers.length / this.usersPerPage);
                    this.currentPage = 1;
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                init() {
                    this.filterUsers();
                    this.isLoading = false;
                }
            };
        }
    </script>
@endsection
