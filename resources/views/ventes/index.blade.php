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
                                    <button @click="ExportRapport" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
                                    </button>



                                    <a class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        href="{{ route('ventes.create') }}">
                                        <i class="fa fa-plus"></i> Création
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
                                                <th class="min-w-125px">Code Vente</th>
                                                <th class="min-w-125px">Client </th>
                                                <th class="min-w-125px">Montant TTC</th>
                                                <th class="min-w-125px">Statut</th>
                                                <th class="min-w-125px">Date créat
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
                                                                x-text="user.numvente"></a>
                                                            <span x-text="user.nom"></span>
                                                        </div>
                                                    </td>
                                                    <td x-text="user.nom"></td>


                                                    <td x-text="user.montantttc"></td>
                                                    <td x-text="user.status"></td>
                                                    <td x-text="new Date(user.created_at).toLocaleDateString('fr-FR')"></td>

                                                    <td class="text-end">
                                                        <!-- Affiche les actions si le statut est "en attente" -->
                                                        <template x-if="user.status === 'en attente'">
                                                            <div>
                                                                <!-- Bouton pour modifier la vente -->
                                                                <a :href="`/ventes/${user.numvente}/edit`"
                                                                    class="btn btn-primary btn-sm mx-2">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>

                                                                <!-- Bouton pour générer la facture -->
                                                                <button @click="generateFacture(user.numvente)"
                                                                    class="btn btn-dark btn-sm mx-2">
                                                                    <i class='far fa-clone'></i>
                                                                </button>

                                                                <!-- Bouton pour supprimer la vente -->
                                                                <button @click="deleteVente(user.id)"
                                                                    class="btn btn-danger btn-sm mx-2">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>

                                                                <!-- Bouton pour valider la vente -->
                                                                <button @click="validateVente(user.numvente)"
                                                                    class="btn btn-success btn-sm mx-2">
                                                                    <i class="fa fa-check"></i> Valider
                                                                </button>
                                                            </div>
                                                        </template>

                                                        <!-- Affiche uniquement le bouton "imprimer" si le statut est "validée" -->
                                                        <template x-if="user.status === 'valide'">
                                                            <div>
                                                                <!-- Bouton pour générer la facture -->
                                                                <button @click="generateFacture(user.numvente)"
                                                                    class="btn btn-dark btn-sm mx-2">
                                                                    <i class='far fa-clone'></i> Imprimer
                                                                </button>
                                                            </div>
                                                        </template>
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
                modules: 'ventes',
                factures: @json($listeventes),
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

                validateVente(numvente) {


                    Swal.fire({
                        title: "Êtes-vous sûr de vouloir valider cette vente ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Oui, valider !",
                        cancelButtonText: "Annuler",
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const url =
                                    `{{ route('ventes.validate', ['vente' => '__ID__']) }}`.replace(
                                        "__ID__",
                                        numvente
                                    );

                                const response = await fetch(url, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
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

                                        const index = this.factures.findIndex(
                                            (facture) => facture.numvente === numvente
                                        );
                                        if (index !== -1) {
                                            this.factures[index].status = "valide";
                                        }

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
                        }
                    });
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
                        user.numvente && user.numvente.toLowerCase().includes(term) ||
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

                async deleteVente(numvente) {
                    try {
                        const url =
                            `{{ route('ventes.destroy', ['vente' => '__ID__']) }}`.replace(
                                "__ID__",
                                numvente
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
                                this.factures = this.factures.filter(factrue => factrue.id !== numvente);

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

                init() {
                    this.filterUsers(); // Initial filter
                    this.isLoading = false;
                }
            };
        }
    </script>
@endsection
