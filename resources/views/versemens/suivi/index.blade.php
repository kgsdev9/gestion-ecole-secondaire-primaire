@extends('layouts.app')
@section('title', 'Suvii des versements')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="versementApp()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            SUIVI DES VERSEMENTS DES ELEVES
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
                    <div class="card">

                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class='fas fa-search position-absolute ms-5'></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher" x-model="searchTerm">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select x-model="classe_id" @change="filterClasse"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les classes </option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <select x-model="annee_id" @change="filterYear"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les annees academique </option>
                                            <template x-for="annee in allAnneesAcademique" :key="annee.id">
                                                <option :value="annee.id" x-text="annee.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <button @click="printProducts" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportProducts" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
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
                                                <th class="min-w-125px">Élève</th>
                                                <th class="min-w-125px">Classe / Matricule</th>
                                                <th class="min-w-125px">Versements</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="(eleve, index) in filteredVersements" :key="index">
                                                <tr>
                                                    <td class="d-flex align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span x-text="eleve.nom + ' ' + eleve.prenom"></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span x-text="'Classe: ' + eleve.classe.name"></span><br>
                                                        <span x-text="'Matricule: ' + eleve.matricule"></span>
                                                    </td>
                                                    <td>
                                                        <template x-for="versement in eleve.versements" :key="versement.id">
                                                            <div class="d-flex justify-content-between">
                                                                <span x-text="'Montant: ' + new Intl.NumberFormat('fr-FR').format(versement.montant_verse) + ' CFA'"></span>
                                                                <span x-text="'Date: ' + new Date(versement.date_versement).toLocaleDateString('fr-FR')"></span>
                                                            </div>

                                                            <!-- Affichage du montant de la scolarité en vert -->
                                                            <div class="d-flex justify-content-between">
                                                                <!-- Débogage de la scolarité -->
                                                                <template x-if="versement.scolarite && versement.scolarite.montant_scolarite">
                                                                    <span class="text-success" x-text="'Scolarité: ' + new Intl.NumberFormat('fr-FR').format(versement.scolarite.montant_scolarite) + ' CFA'"></span>
                                                                </template>
                                                                <template x-if="!(versement.scolarite && versement.scolarite.montant_scolarite)">
                                                                    <span class="text-muted">Pas de scolarité disponible</span>
                                                                </template>

                                                                <!-- Vérification dans la console pour la scolarité -->
                                                                <template x-if="versement.scolarite">
                                                                    <span x-text="console.log(versement.scolarite)"></span>
                                                                </template>
                                                            </div>

                                                        </template>
                                                    </td>

                                                    <td class="text-end">
                                                        <button @click="printVersement(eleve.id)"
                                                            class="btn btn-danger btn-sm">
                                                            <i class="fa fa-print"></i>
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
    </div>

    <script>
        function versementApp() {
            return {
                searchTerm: '',
                annee_id: '',
                classe_id: '', // Ajout du modèle pour la classe
                classes: @json($classes), // On suppose que tu passes les classes dans la vue
                allAnneesAcademique: @json($allAnneesAcademique),
                versements: @json($versements),
                isLoading: false,
                currentPage: 1,
                productsPerPage: 10,
                totalPages: 0,

                // Regrouper les versements par élève et ajouter la classe et matricule
                get groupedVersements() {
                    let grouped = [];
                    this.versements.forEach(versement => {
                        let eleve = grouped.find(eleve => eleve.id === versement.eleve_id);
                        if (!eleve) {
                            eleve = {
                                id: versement.eleve_id,
                                nom: versement.eleve.nom,
                                prenom: versement.eleve.prenom,
                                classe: versement.eleve.classe, // Ajouter la classe
                                matricule: versement.eleve.matricule, // Ajouter le matricule
                                versements: [],
                            };
                            grouped.push(eleve);
                        }
                        eleve.versements.push(versement);
                    });
                    return grouped;
                },

                // Fonction pour filtrer par classe
                filterClasse() {
                    this.currentPage = 1; // Remettre à la première page lors du filtrage
                },

                // Recherche filtrée sur les données (ajout de la classe au filtrage)
                get filteredVersements() {
                    let filtered = this.groupedVersements;

                    // Filtrage sur la classe
                    if (this.classe_id) {
                        filtered = filtered.filter(eleve => eleve.classe.id == this.classe_id);
                    }

                    // Filtrage par recherche texte
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(eleve => {
                            return (
                                eleve.nom.toLowerCase().includes(term) ||
                                eleve.prenom.toLowerCase().includes(term) ||
                                eleve.classe?.name?.toLowerCase().includes(term) ||
                                eleve.matricule.toLowerCase().includes(term)
                            );
                        });
                    }

                    return filtered;
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                init() {
                    this.isLoading = false;
                },

                printVersement(id) {
                    // Logic for printing a versement
                }
            };
        }
    </script>
@endsection
