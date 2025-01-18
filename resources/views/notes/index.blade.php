@extends('layouts.app')
@section('title', 'Gestion des notes')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="notesManager()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES NOTES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Notes</li>
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
                                    <i class="fas fa-search position-absolute ms-5"></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select @change="filterClasse" class="form-select form-select-sm"
                                            data-live-search="true">
                                            <option value="">Toutes les classes</option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <select @change="filterLevel" class="form-select form-select-sm"
                                            data-live-search="true">
                                            <option value="">Tous les niveaux</option>
                                            <template x-for="niveau in niveaux" :key="niveau.id">
                                                <option :value="niveau.id" x-text="niveau.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body py-4">
                            <div class="container mt-5">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <input type="text"
                                            class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                            placeholder="Rechercher" @input="filterProducts">
                                    </div>
                                    <div class="col-md-3">
                                        <select @change="filterByCategory" class="form-select form-select-sm"
                                            data-live-search="true">
                                            <option value="">Toutes les classes</option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text"
                                            class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                            placeholder="Entrer une note">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                            <i class="fa fa-add"></i> Création
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <template x-for="matiere in matieres" :key="matiere.id">
                                                    <th x-text="matiere.name"></th>
                                                </template>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="eleve in eleves" :key="eleve.id">
                                                <tr>
                                                    <td x-text="eleve.eleve.nom"></td>
                                                    <td x-text="eleve.eleve.prenom"></td>
                                                    <template x-for="matiere in matieres" :key="matiere.id">
                                                        <td>
                                                            <button class="btn btn-primary btn-sm"
                                                                @click="openModal(eleve.eleve.id, matiere.id)">
                                                                <span x-show="getNotes(eleve.id, matiere.id).length > 0">
                                                                    Voir notes
                                                                </span>
                                                                <span
                                                                    x-show="getNotes(eleve.eleve.id, matiere.id).length === 0">
                                                                    Aucune note
                                                                </span>
                                                            </button>
                                                        </td>
                                                    </template>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm"
                                                            @click="deleteEleve(eleve.id)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- news modal --}}
                        <template x-if="showModal">
                            <div class="modal fade show d-block" tabindex="-1" aria-modal="true"
                                style="background-color: rgba(0,0,0,0.5)">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Notes pour <span x-text="currentMatiere.name"></span>
                                            </h5>
                                            <button type="button" class="btn-close" @click="closeModal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <template x-if="currentNotes.length > 0">
                                                <template x-for="note in currentNotes" :key="note.id">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <p class="mb-1"><strong>Note :</strong> <span
                                                                    x-text="note.note"></span></p>
                                                            <p class="mb-0"><strong>Type :</strong>
                                                                <!-- Vérification si typenote est défini -->
                                                                <span
                                                                    x-text="note.typenote ? note.typenote.name : 'Inconnu'"></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </template>
                                            <template x-if="currentNotes.length === 0">
                                                <p class="text-muted">Aucune note disponible.</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        {{-- fin news modal --}}
                    </div>
                </div>
            </div>
        </div>

        <script>
            function notesManager() {
                return {
                    eleves: @json($eleves),
                    matieres: @json($matieres),
                    classes: @json($classes),
                    niveaux: @json($niveaux),
                    showModal: false,
                    currentNotes: [],
                    currentMatiere: {},
                    currentEleveId: null,
                    filteredEleves: [],
                    filteredClasses: [],
                    filteredNiveaux: [],
                    init() {
                        this.filteredEleves = this.eleves;
                        this.filteredClasses = this.classes;
                        this.filteredNiveaux = this.niveaux;
                    },
                    getNotes(eleveId, matiereId) {
                        const eleve = this.eleves.find(e => e.eleve.id === eleveId);
                        if (!eleve) {
                            console.log('Élève non trouvé');
                            return [];
                        }

                        if (!eleve.eleve.notes || eleve.eleve.notes.length === 0) {
                            console.log('Aucune note trouvée pour cet élève');
                            return [];
                        }

                        const notes = eleve.eleve.notes.filter(note => note.matiere_id === matiereId);
                        console.log('Notes trouvées :', notes);
                        return notes;
                    },

                    openModal(eleveId, matiereId) {
                        this.currentEleveId = eleveId;
                        this.currentMatiere = this.matieres.find(m => m.id === matiereId) || {};
                        this.currentNotes = this.getNotes(eleveId, matiereId);

                        console.log('Current Notes:', this.currentNotes); // Ajout d'un log pour déboguer

                        this.showModal = true;
                    },


                    closeModal() {
                        this.isModalOpen = false;
                        this.currentNotes = [];
                        this.currentMatiere = {};
                        this.showModal = false;

                    },

                    deleteEleve(eleveId) {
                        if (confirm("Êtes-vous sûr de vouloir supprimer cet élève ?")) {
                            // Ajoutez ici l'appel pour supprimer l'élève via AJAX ou API.
                            this.eleves = this.eleves.filter(e => e.id !== eleveId);
                            alert("Élève supprimé avec succès.");
                        }
                    },

                    filterClasse(event) {
                        const classeId = event.target.value;
                        if (!classeId) {
                            this.filteredEleves = this.eleves; // Afficher tous les élèves
                        } else {
                            this.filteredEleves = this.eleves.filter(eleve => eleve.classe_id === parseInt(classeId));
                        }
                    },

                    filterLevel(event) {
                        const niveauId = event.target.value;
                        if (!niveauId) {
                            this.filteredEleves = this.eleves; // Afficher tous les élèves
                        } else {
                            this.filteredEleves = this.eleves.filter(eleve => eleve.niveau_id === parseInt(niveauId));
                        }
                    },

                    filterProducts(event) {
                        const search = event.target.value.toLowerCase();
                        this.filteredEleves = this.eleves.filter(eleve => {
                            const fullName = `${eleve.eleve.nom} ${eleve.eleve.prenom}`.toLowerCase();
                            return fullName.includes(search);
                        });
                    }
                };
            }
        </script>

    @endsection
