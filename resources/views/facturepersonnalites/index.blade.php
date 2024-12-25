@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="userSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES FACTURES PERSONNALISEE
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Facture personalisée</li>
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
                                        <i class='fas fa-file-export'></i>Export
                                    </button>
                                    <a href="{{ route('facturepersonnalite.create') }}"
                                        class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                        <i class="fa fa-add"></i> Création
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
                                                                <i class='far fa-clone'></i>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="#" class="text-gray-800 text-hover-primary mb-1"
                                                                x-text="user.codefacture"></a>
                                                            <span x-text="user.client.codeclient"></span>
                                                        </div>
                                                    </td>
                                                    <td x-text="user.client.libtiers"></td>
                                                    <td x-text="user.montanttva"></td>
                                                    <td x-text="user.montantttc"></td>
                                                    <td x-text="new Date(user.created_at).toLocaleDateString('fr-FR')">
                                                    <td class="text-end">
                                                        <a :href="`/facturepersonnalite/${user.codefacture}/edit`"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button @click="generateFacture(user.codefacture)"
                                                            class="btn btn-dark ms-2 btn-sm mx-2">
                                                            <i class='far fa-clone'></i>
                                                        </button>

                                                        <button @click="deleteFacture(user.codefacture)"
                                                            class="btn btn-danger ms-2 btn-sm mx-2">
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
    </div>


    <script>
        function userSearch() {
            return {
                searchTerm: '',
                factures: @json($listefactures),
                filteredUsers: [],
                currentPage: 1,
                usersPerPage: 10,
                modules: 'fapersonalise',
                totalPages: 0,
                isLoading: true,
                showModal: false,

                async printRapport() {
                    const search = encodeURIComponent(this.searchTerm);
                    const modules = encodeURIComponent(this.modules);

                    try {
                        // Appelle la route avec le critère de recherche dans l'URL
                        const response = await fetch(`/generateRapport?search=${search}&modules=${modules}`, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                        });

                        if (response.ok) {
                            // Récupère le PDF sous forme de blob
                            const blob = await response.blob();

                            // Crée une URL pour afficher ou télécharger le PDF
                            const url = window.URL.createObjectURL(blob);

                            // Ouvre le PDF dans une nouvelle fenêtre
                            window.open(url, '_blank');
                        } else {
                            console.error('Erreur lors de la récupération du PDF.');
                        }
                    } catch (error) {
                        console.error('Une erreur est survenue :', error);
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


                async deleteFacture(codeFacture) {
                    try {
                        const url =
                            `{{ route('facturepersonnalite.destroy', ['facturepersonnalite' => '__ID__']) }}`.replace(
                                "__ID__",
                                codeFacture
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
                                this.factures = this.factures.filter(facture => facture.codefacture !== codeFacture);

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


                filterUsers() {
                    const term = this.searchTerm.toLowerCase();
                    this.filteredUsers = this.factures.filter(user =>
                        user.codefacture && user.codefacture.toLowerCase().includes(term) ||
                        user.client.libtiers && user.client.libtiers.toLowerCase().includes(term)


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
