@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="userSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                            class="path1"></span><span class="path2"></span></i>
                                    <input type="text" class="form-control form-control-solid w-250px ps-13"
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterUsers">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-light-primary me-3">
                                        <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> Imprimer
                                    </button>
                                    <button type="button" class="btn btn-light-primary me-3">
                                        <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span
                                                class="path2"></span></i> Export
                                    </button>

                                    <a type="button" class="btn btn-primary btn-sm" href="{{ route('factures.create') }}">
                                        <i class="ki-duotone ki-plus fs-2"></i> Création
                                    </a>
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
                                                <th class="min-w-125px">Code facture</th>
                                                <th class="min-w-125px">Client </th>
                                                <th class="min-w-125px">Montant tva</th>
                                                <th class="min-w-125px">Montant TTC</th>
                                                <th class="min-w-125px">Date création</th>

                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="user in paginatedUsers" :key="user.id">
                                                <tr>
                                                    <td class="d-flex align-items-center">
                                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                            <div class="symbol-label">

                                                                <span class="menu-icon"><i
                                                                        class="ki-duotone ki-file fs-2"><span
                                                                            class="path1"></span><span
                                                                            class="path2"></span></i></span>


                                                            </div>

                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="#" class="text-gray-800 text-hover-primary mb-1"
                                                                x-text="user.codefacture"></a>
                                                            <span x-text="user.nom"></span>
                                                        </div>
                                                    </td>
                                                    <td x-text="user.prenom"></td>
                                                    <td x-text="user.montanttva"></td>

                                                    <td x-text="user.montantttc"></td>
                                                    <td x-text="user.created_at"></td>
                                                    <td class="text-end">
                                                        <a :href="`/factures/${user.codefacture}/edit`" class="edit-link">
                                                            <svg width="20" height="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 5.29a1.003 1.003 0 0 0 0-1.42l-2.58-2.58a1.003 1.003 0 0 0-1.42 0L15.21 3.89l3 3 2.5-2.6z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </a>
                                                        <button @click="generateFacture(user.codefacture)"
                                                            class="btn btn-primary ms-2 btn-sm">
                                                            Télecharger
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
    </div>


    <script>
        function userSearch() {
            return {
                searchTerm: '',
                factures: @json($listefactures),
                filteredUsers: [],
                currentPage: 1,
                usersPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                formData: {
                    name: '',
                    email: '',
                    password: '',
                },

                hideModal() {
                    this.showModal = false; // Close modal
                },

                async submitForm() {
                    this.isLoading = true;
                    try {
                        const response = await fetch('{{ route('users.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(this.formData),
                        });

                        if (response.ok) {
                            const newUser = await response.json();

                            // Recharger tous les utilisateurs après ajout
                            this.users.push(newUser);
                            this.filterUsers();

                            // Réinitialiser le formulaire et fermer le modal
                            this.formData = {
                                name: '',
                                email: '',
                                password: ''
                            };
                            this.hideModal(); // Close the modal after submission
                        } else {
                            console.error('Erreur lors de l\'enregistrement.');
                        }
                    } catch (error) {
                        console.error('Erreur réseau :', error);
                    } finally {
                        this.isLoading = false;
                    }
                },



                generateFacture(codefacture) {
                    // Envoi d'une requête GET pour générer la facture
                    fetch(`/generatefacture/${codefacture}`, {
                            method: 'GET', // Utilisation de la méthode GET
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ajoutez le token CSRF
                            },
                        })
                        .then(response => response.blob()) // Si c'est un fichier (PDF)
                        .then(blob => {
                            // Créer un objet URL pour le fichier
                            const link = document.createElement('a');
                            link.href = URL.createObjectURL(blob);
                            link.download = `facture_${codefacture}.pdf`; // Nom du fichier
                            link.click(); // Téléchargement automatique
                        })
                        .catch(error => {
                            console.error('Erreur lors de la génération de la facture:', error);
                        });
                },


                get paginatedUsers() {
                    let start = (this.currentPage - 1) * this.usersPerPage;
                    let end = start + this.usersPerPage;
                    return this.filteredUsers.slice(start, end);
                },

                filterUsers() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredUsers = this.factures.filter(user =>
                        user.codefacture && user.codefacture.toLowerCase().includes(term) ||
                        user.nom && user.nom.toLowerCase().includes(term) ||
                        user.prenom && user.prenom.toLowerCase().includes(term)

                    );
                    this.totalPages = Math.ceil(this.filteredUsers.length / this.usersPerPage);
                    this.currentPage = 1; // Reset to first page on search
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                init() {
                    this.filterUsers(); // Initial filter
                    this.isLoading = false;
                }
            };
        }
    </script>
@endsection
