@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="versementManager()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES VERSEMENTS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Versements</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="d-flex flex-column flex-xl-row">

                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body pt-15">
                                    <div class="d-flex flex-center flex-column mb-5">
                                        <!-- Avatar -->
                                        <div class="symbol symbol-150px symbol-circle mb-7">
                                            <img src="{{ asset('avatar.png') }}" alt="image">
                                        </div>

                                        <!-- Nom de l'élève -->
                                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                                            <span x-text="selectedEleve.nom"></span></a>
                                        <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                                            <span x-text="selectedEleve.email"></span></a>
                                    </div>

                                    <div class="custom-select-container">
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle w-100" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <template x-if="selectedEleve.nom">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('avatar.png') }}" alt="Avatar"
                                                            class="rounded-circle me-2" width="40" height="40" />
                                                        <div>
                                                            <strong x-text="selectedEleve.nom"></strong>
                                                            <br />
                                                            <span class="text-muted"
                                                                x-text="selectedEleve.matricule"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                <span x-text="selectedEleve.nom ? '' : 'Aucun élève sélectionné'"></span>
                                            </button>

                                            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                                <li class="px-2 py-1">
                                                    <input type="text" class="form-control" placeholder="Rechercher..."
                                                        x-model="searchQuery" />
                                                </li>

                                                <template x-for="eleve in filteredEleves()" :key="eleve.id">
                                                    <li class="dropdown-item d-flex align-items-center"
                                                        @click="updateEleveInfo(eleve.id, eleve.nom, eleve.prenom, eleve.matricule, eleve.telephone_parent, 'https://via.placeholder.com/40', eleve.classe_id, eleve.niveau_id, eleve.anneeacademique_id)"
                                                        style="cursor: pointer;">
                                                        <img src="{{ asset('avatar.png') }}" alt="Avatar"
                                                            class="rounded-circle me-2" width="40" height="40" />
                                                        <div>
                                                            <strong x-text="eleve.nom"></strong>
                                                            <br />
                                                            <span class="text-muted" x-text="eleve.matricule"></span>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                            <div class="pb-5 fs-6">
                                                <div class="fw-bold mt-5">Montant De la Scolarite</div>
                                                <div class="text-gray-600">
                                                    <span
                                                        x-text="selectedEleve.montantScolarite || 'Aucune scolarité trouvée'"></span>
                                                </div>

                                                <div class="fw-bold mt-5">Montant Payé</div>
                                                <div class="text-gray-600">
                                                    <span x-text="totalMontantVerse() || '0'"></span>
                                                </div>

                                                <div class="fw-bold mt-5">Montant Réliquat</div>
                                                <div class="text-gray-600">
                                                    <span x-text="totalMontantReste() || '0'"></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historique des versements -->
                        <div class="flex-lg-row-fluid ms-lg-15">
                            <div class="card pt-4 mb-6 mb-xl-9">
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
                                            <button @click="printRapport" class="btn btn-light-dark btn-sm">
                                                <i class="fa fa-print"></i> Imprimer
                                            </button>
                                            <button @click="exportRaport" class="btn btn-light-success btn-sm">
                                                <i class='fas fa-file-export'></i> Export
                                            </button>
                                            <button @click="showModal = true"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                                <i class="fa fa-add"></i> Création
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <div class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5 dataTable"
                                                style="width: 100%;">
                                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                    <tr class="text-start text-muted text-uppercase gs-0">
                                                        <th class="min-w-100px">Élève</th>
                                                        <th class="min-w-100px">Montant Versé</th>
                                                        <th class="min-w-100px">Montant Restant</th>
                                                        <th class="min-w-100px">Type Versement</th>
                                                        <th class="min-w-100px">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                    <template x-for="versement in filteredVersements()"
                                                        :key="versement.id">
                                                        <tr>
                                                            <td x-text="versement.eleve.nom"></td>
                                                            <td x-text="versement.montant_verse"></td>
                                                            <td x-text="versement.montant_restant"></td>
                                                            <td x-text="versement.type_versement.name"></td>
                                                            <td x-text="versement.date_versement"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- modal versemetn --}}
                        <template x-if="showModal">
                            <div class="modal fade show d-block" tabindex="-1" aria-modal="true"
                                style="background-color: rgba(0,0,0,0.5)">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                                            <button type="button" class="btn-close" @click="hideModal()"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form @submit.prevent="submitForm">

                                                <!-- Type Versement -->
                                                <div class="mb-3">
                                                    <label for="typeversement_id" class="form-label">Type
                                                        Versement</label>
                                                    <select id="typeversement_id" class="form-control"
                                                        x-model="formData.typeversement_id" required>
                                                        <option value="">Sélectionner un type de versement</option>
                                                        <!-- Remplir les options avec les types de versements disponibles -->
                                                        <template x-for="type in typesVersement" :key="type.id">
                                                            <option :value="type.id" x-text="type.name"></option>
                                                        </template>
                                                    </select>
                                                </div>

                                                <!-- Date Versement -->
                                                <div class="mb-3">
                                                    <label for="date_versement" class="form-label">Date du
                                                        Versement</label>
                                                    <input type="date" id="date_versement" class="form-control"
                                                        x-model="formData.date_versement" required>
                                                </div>

                                                <!-- Montant Versement -->
                                                <div class="mb-3">
                                                    <label for="montant_verse" class="form-label">Montant Versé</label>
                                                    <input type="number" id="montant_verse" class="form-control"
                                                        x-model="formData.montant_verse" required>
                                                </div>

                                                <!-- Montant Restant -->
                                                <div class="mb-3">
                                                    <label for="montant_restant" class="form-label">Montant
                                                        Restant</label>
                                                    <input type="number" id="montant_restant" class="form-control"
                                                        x-model="formData.montant_restant" required>
                                                </div>

                                                <!-- Bouton de soumission -->
                                                <button type="submit" class="btn btn-primary"
                                                    x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </template>
                        {{-- fin modal versement --}}


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function versementManager() {
            return {
                searchQuery: '',
                showModal: '',
                eleves: @json($listeleves),
                versements: @json($versements),
                scolarites: @json($listescolarite),
                selectedEleve: {
                    id: '',
                    nom: '',
                    prenom: '',
                    matricule: '',
                    email: '',
                    avatar: 'https://via.placeholder.com/40',
                    classe_id: '',
                    niveau_id: '',
                    annee_academique_id: '',
                    montantScolarite: '',
                },
                currentVersement: null,
                isEdite: false,

                // Filtrer les élèves en fonction de la recherche
                filteredEleves() {
                    return this.eleves.filter(eleve => eleve.nom.toLowerCase().includes(this.searchQuery.toLowerCase()));
                },

                hideModal() {
                    this.showModal = false;
                    this.currentVersement = null;
                    // this.resetForm();
                    this.isEdite = false;
                },
                // Mettre à jour les informations de l'élève sélectionné
                updateEleveInfo(id, nom, prenom, matricule, email, avatar, classe_id, niveau_id, annee_academique_id) {
                    this.selectedEleve = {
                        id: id,
                        nom: nom || '',
                        prenom: prenom || '',
                        matricule: matricule || '',
                        email: email || '',
                        avatar: avatar || 'https://via.placeholder.com/40',
                        classe_id: classe_id,
                        niveau_id: niveau_id,
                        annee_academique_id: annee_academique_id,
                    };

                    // Filtrer les versements pour l'élève sélectionné
                    this.filteredVersements();
                    // Filtrer la scolarité de l'élève
                    this.filterScolarite();
                },

                // Filtrer la scolarité de l'élève
                filterScolarite() {
                    const scolarite = this.scolarites.find(s =>
                        s.niveau_id === this.selectedEleve.niveau_id &&
                        s.classe_id === this.selectedEleve.classe_id &&
                        s.annee_academique_id === this.selectedEleve.annee_academique_id
                    );


                    // Si une scolarité est trouvée, mettre à jour le montant de la scolarité
                    if (scolarite) {

                        this.selectedEleve.montantScolarite = scolarite.montant_scolarite;
                    } else {
                        this.selectedEleve.montantScolarite = 0;
                    }
                },

                totalMontantVerse() {
                    const versements = this.filteredVersements();
                    return versements.reduce((total, versement) => total + parseFloat(versement.montant_verse || 0), 0);
                },

                // Calculer la somme des montants restants pour l'élève sélectionné
                totalMontantReste() {
                    const montantVerse = this.totalMontantVerse();
                    const montantScolarite = this.selectedEleve.montantScolarite || 0;
                    return montantScolarite - montantVerse;
                },

                // Filtrer les versements pour l'élève sélectionné
                filteredVersements() {
                    // Si un élève est sélectionné, filtrer les versements par élève_id
                    if (this.selectedEleve.id) {
                        return this.versements.filter(versement => versement.eleve_id == this.selectedEleve.id);
                    }
                    // Si aucun élève n'est sélectionné, retourner un tableau vide
                    return [];
                },
            };
        }
    </script>
@endpush
