@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="roleManagement()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES ROLES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Roles</li>
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
                                        placeholder="Rechercher"x-model="searchTerm" @input="filterRoles">
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="printRoles" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportRoles" class="btn btn-light-primary btn-sm">
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
                                                <th class="min-w-125px">Libellé Rôles</th>
                                                <th class="min-w-125px">Date de création</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="role in paginatedRoles" :key="role.id">
                                                <tr>
                                                    <td x-text="role.libellerole"></td>
                                                    <td x-text="formatDate(role.created_at)"></td>
                                                    <td class="text-end">
                                                        <button @click="openModal(role)"
                                                            class="btn btn-primary ms-2 btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteRole(role.id)" class="btn btn-danger btn-sm">
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
                            <h5 class="modal-title" x-text="isEdit ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="closeModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="mb-3">
                                    <label for="role_name" class="form-label">Libellé Rôle</label>
                                    <input type="text" id="role_name" class="form-control" x-model="formData.libellerole"
                                        required>
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
        function roleManagement() {
            return {
                searchTerm: '',
                roles: @json($listeroles),
                filteredRoles: [],
                currentPage: 1,
                rolesPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdit: false,
                formData: {
                    libellerole: '',
                },
                currentRole: null,

                init() {
                    this.filterRoles();
                    this.isLoading = false;
                },

                openModal(role = null) {
                    this.isEdit = !!role;
                    if (this.isEdit) {
                        this.currentRole = {
                            ...role
                        };
                        this.formData.libellerole = role.libellerole;
                    } else {
                        this.resetForm();
                    }
                    this.showModal = true;
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                closeModal() {
                    this.showModal = false;
                    this.resetForm();
                },

                resetForm() {
                    this.formData = {
                        libellerole: ''
                    };
                    this.isEdit = false;
                    this.currentRole = null;
                },

                filterRoles() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredRoles = this.roles.filter(role => role.libellerole.toLowerCase().includes(term));
                    this.totalPages = Math.ceil(this.filteredRoles.length / this.rolesPerPage);
                    this.currentPage = 1;
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('fr-FR');
                },

                get paginatedRoles() {
                    const start = (this.currentPage - 1) * this.rolesPerPage;
                    return this.filteredRoles.slice(start, start + this.rolesPerPage);
                },

                async submitForm() {
                    this.isLoading = true;

                    if (!this.formData.libellerole || this.formData.libellerole.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom du role est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('libellerole', this.formData.libellerole);
                    formData.append('role_id', this.currentRole ? this.currentRole.id : null);

                    try {
                        const response = await fetch('{{ route('roles.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const role = data.role;
                            console.log('Rôle reçu depuis le backend :', role);

                            if (role) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Role enregistré avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                if (this.isEdit) {
                                    const index = this.roles.findIndex(p => p.id === role.id);
                                    if (index !== -1) {
                                        this.roles[index] = role;
                                    }
                                } else {
                                    this.roles.push(role);
                                    this.roles.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }

                                this.filterRoles(); // Met à jour la liste des rôles affichée
                                this.resetForm();
                                this.closeModal();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Produit non valide.',
                                    showConfirmButton: true
                                });
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

                exportRoles() {
                    let csvContent = "ID,Libelle role,\n";

                    this.filteredRoles.forEach(role => {
                        csvContent +=
                            `${role.id},${role.libellerole}\n`;
                    });
                    const blob = new Blob([csvContent], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const link = document.createElement("a");
                    const url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", "roles.csv");
                    link.click();
                },

                printRoles() {
                    let printContent = '<h1>Liste des Produits</h1>';
                    printContent +=
                        '<table border="1"><thead><tr><th>ID</th><th>Nom</th></tr></thead><tbody>';

                    this.filteredRoles.forEach(role => {
                        printContent +=
                            `<tr><td>${role.id}</td><td>${role.libellerole}</td></tr>`;
                    });

                    printContent += '</tbody></table>';

                    const printWindow = window.open('', '', 'height=500,width=800');
                    printWindow.document.write('<html><head><title>Impression des roles</title></head><body>');
                    printWindow.document.write(printContent);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                },


                async deleteRole(roleID) {
                    try {
                        const url =
                            `{{ route('roles.destroy', ['role' => '__ID__']) }}`.replace(
                                "__ID__",
                                roleID
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
                                this.roles = this.roles.filter(role => role.id !== roleID);

                                // Après suppression, appliquer le filtre pour mettre à jour la liste affichée
                                this.filterRoles();
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

            };
        }
    </script>
@endsection
