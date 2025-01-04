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
                                    <button @click="openModal()"
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
                                                <th class="min-w-125px">Email</th>
                                                <th class="min-w-125px">Role </th>
                                                <th class="min-w-125px">Date de création </th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="user in paginatedUsers" :key="user.id">
                                                <tr>
                                                    <td x-text="user.name"></td>
                                                    <td x-text="user.email"></td>
                                                    <td x-text="user.role?.libellerole ?? 'Aucun rôle'"></td>

                                                    <td x-text="new Date(user.created_at).toLocaleDateString('fr-FR')"></td>
                                                    <td class="text-end">
                                                        <button @click="openModal(user)"
                                                            class="btn btn-primary ms-2 btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>


                                                        <button @click="deleteUsers(user.id)" class="btn btn-danger btn-sm">
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" class="form-control" x-model="formData.name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" x-model="formData.email"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Mot de passe</label>
                                    <input type="password" id="email" class="form-control" x-model="formData.password">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Role </label>
                                    <select x-model="formData.role_id" class="form-select">
                                        <option value="">Choisir un role </option>
                                        @foreach ($listeroles as $role)
                                            <option value="{{ $role->id }}">{{ $role->libellerole }}
                                            </option>
                                        @endforeach
                                    </select>
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
        function userSearch() {
            return {
                searchTerm: '',
                users: @json($users),
                filteredUsers: [],
                currentPage: 1,
                usersPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdite: false,
                formData: {
                    name: '',
                    email: '',
                    role_id: '',
                    password: ''
                },
                currentUser: null,

                hideModal() {
                    this.showModal = false;
                    this.currentUser = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(user = null) {
                    this.isEdite = user !== null;

                    if (this.isEdite) {
                        this.currentUser = {
                            ...user
                        };

                        // Vérification si `user` et `user.role` existent avant d'accéder à `id`
                        this.formData = {
                            name: this.currentUser.name,
                            email: this.currentUser.email,
                            role_id: this.currentUser.role ? this.currentUser.role.id : null,
                            user_id: this.currentUser.id ?? null
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

                async submitForm() {
                    this.isLoading = true;
                    if (!this.formData.name || this.formData.name.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    if (!this.formData.email || this.formData.email.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Email est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.role_id || this.formData.role_id.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Role id est réquis .',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    const formData = new FormData();
                    formData.append('name', this.formData.name);
                    formData.append('email', this.formData.email);
                    formData.append('password', this.formData.password);
                    formData.append('role_id', this.formData.role_id);
                    formData.append('user_id', this.currentUser ? this.currentUser.id : null);

                    try {
                        const response = await fetch('{{ route('users.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const user = data.user;

                            if (user) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Utilisateur enregistré avec succès!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                if (this.isEdite) {
                                    const index = this.users.findIndex(u => u.id === user.id);
                                    if (index !== -1) this.users[index] = user;
                                } else {
                                    this.users.push(user);
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
                        return (user.name && user.name.toLowerCase().includes(term)) ||
                            (user.role && user.role.libellerole && user.role.libellerole.toLowerCase().includes(
                                term));
                    });
                    this.totalPages = Math.ceil(this.filteredUsers.length / this.usersPerPage);
                    this.currentPage = 1;
                },

                async deleteUsers(UsersId) {
                    try {
                        const url =
                            `{{ route('users.destroy', ['user' => '__ID__']) }}`.replace(
                                "__ID__",
                                UsersId
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

                                // Retirer le produit de la liste `this.products`
                                this.users = this.users.filter(user => user.id !== UsersId);

                                // Après suppression, appliquer le filtre pour mettre à jour la liste affichée
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
