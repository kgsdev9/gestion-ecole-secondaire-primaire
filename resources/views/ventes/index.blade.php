@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="userSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES VENTES DU JOUR
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Gestion des ventes </li>
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

                                    <div>
                                        <select x-model="selectModereglement" @change="filterVentes"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Choisir un mode reglement </option>
                                            <template x-for="modereglement in listemodereglement" :key="modereglement.id">
                                                <option :value="modereglement.id"
                                                    x-text="modereglement.libellemodereglement">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <select x-model="selectTable" @change="filterVentes"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Choisir un table </option>
                                            <template x-for="table in listetablerestaurant" :key="table.id">
                                                <option :value="table.id" x-text="table.name">
                                                </option>
                                            </template>
                                        </select>
                                    </div>


                                    <button @click="printVentes" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="ExportRapport" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
                                    </button>



                                    <a class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        href="{{ route('product.pos') }}">
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
                                                <th class="min-w-125px">Mode re reglement</th>
                                                <th class="min-w-125px">Table</th>
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
                                                    <td x-text="user.modereglement.libellemodereglement"></td>
                                                    <td x-text="user.table.name"></td>
                                                    <td x-text="user.montantttc"></td>
                                                    <td x-text="user.status"></td>
                                                    <td x-text="new Date(user.created_at).toLocaleDateString('fr-FR')"></td>

                                                    <td class="text-end">
                                                        <!-- Affiche les actions si le statut est "en attente" -->
                                                        <template x-if="user.status === 'en attente'">
                                                            <div>
                                                              

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
                listemodereglement: @json($listemodereglement),
                listetablerestaurant: @json($listetablerestaurant),
                filteredUsers: [],
                currentPage: 1,
                usersPerPage: 10,
                totalPages: 0,
                selectModereglement: '',
                selectTable: '',
                isLoading: true,
                showModal: false,
                formData: {
                    name: '',
                    email: '',
                    password: '',
                },

                async printVentes() {


                    const formData = new FormData();

                    // Ajouter les critères de filtrage
                    formData.append('modereglement', this.selectModereglement || ''); // Mode de règlement
                    formData.append('table', this.selectTable || ''); // Table





                    try {
                        // Envoyer une requête POST au backend
                        const response = await fetch('{{ route('facture.vente.rapport') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token CSRF pour sécuriser la requête
                            },
                            body: formData, // Données de la requête
                        });

                        if (response.ok) {
                            // Récupérer le contenu du PDF
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob); // Créer un objet URL pour le blob
                            const link = document.createElement('a'); // Créer un lien de téléchargement
                            link.href = url;
                            link.download = 'rapport_ventes.pdf'; // Spécifier le nom du fichier
                            link.click(); // Simuler un clic pour démarrer le téléchargement

                            // Message de succès avec SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Rapport généré avec succès!',
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        } else {
                            // Message d'erreur en cas d'échec de la requête
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de la génération du rapport.',
                                showConfirmButton: true,
                            });
                        }
                    } catch (error) {
                        console.error('Erreur réseau :', error);

                        // Message d'erreur en cas d'erreur réseau
                        Swal.fire({
                            icon: 'error',
                            title: 'Une erreur est survenue.',
                            showConfirmButton: true,
                        });
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


                filterVentes() {
                    this.filteredUsers = this.factures;
                    if (this.selectModereglement) {
                        this.filteredUsers = this.filteredUsers.filter(facture => facture.modereglement.id === parseInt(this
                            .selectModereglement));
                    }

                    if (this.selectTable) {
                        this.filteredUsers = this.filteredUsers.filter(facture => facture.table && facture.table.id ===
                            parseInt(this.selectTable));
                    }
                    this.totalPages = Math.ceil(this.filteredUsers.length / this.usersPerPage);
                },


                generateFacture(codefacture) {
                    // Envoi d'une requête GET pour générer la facture
                    fetch(`generatefactureVente/${codefacture}`, {
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
