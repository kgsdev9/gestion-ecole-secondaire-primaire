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
                                        placeholder="Rechercher un étudiant" x-model="searchTerm" @input="filterEleves" />

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
                                        <select class="form-select form-select-sm" x-model="formData.matiere_id"
                                            data-live-search="true">
                                            <option value="">Toutes les matieres Matiere </option>
                                            <template x-for="matiere in matieres" :key="matiere.id">
                                                <option :value="matiere.id" x-text="matiere.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" x-model="formData.typenote_id"
                                            data-live-search="true">
                                            <option value="">Toutes les type de note </option>
                                            <template x-for="typenote in typenotes" :key="typenote.id">
                                                <option :value="typenote.id" x-text="typenote.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number"
                                            class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                            x-model="formData.note" placeholder="Entrer une note">
                                    </div>
                                    <div class="col-md-3">
                                        <button @click="submitForm()"
                                            class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                            <i class="fa fa-add"></i> Ajouter
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
                                            <template x-for="eleve in filteredEleves" :key="eleve.id">
                                                <tr>
                                                    <td x-text="eleve.eleve.nom"></td>
                                                    <td x-text="eleve.eleve.prenom"></td>
                                                    <template x-for="matiere in matieres" :key="matiere.id">
                                                        <td>
                                                            <button
                                                                :class="{
                                                                    'btn btn-sm btn-danger': getNotes(eleve.eleve.id,
                                                                        matiere.id).length === 0,
                                                                    'btn btn-sm btn-success': getNotes(eleve.eleve.id,
                                                                        matiere.id).length > 0
                                                                }"
                                                                @click="openModal(eleve.eleve.id, matiere.id)">
                                                                <span
                                                                    x-text="getNotes(eleve.eleve.id, matiere.id).length === 0 ? 'Aucune note' : 'Voir les notes'">
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

                                            {{-- <template x-for="eleve in eleves" :key="eleve.id">
                                                <tr>
                                                    <td x-text="eleve.eleve.nom"></td>
                                                    <td x-text="eleve.eleve.prenom"></td>
                                                    <template x-for="matiere in matieres" :key="matiere.id">
                                                        <td>
                                                            <button
                                                                :class="getNotes(eleve.eleve.id, matiere.id).length === 0 ?
                                                                    'btn-danger' : 'btn-success'"
                                                                class="btn btn-sm"
                                                                @click="openModal(eleve.eleve.id, matiere.id)">
                                                                <span
                                                                    x-text="getNotes(eleve.eleve.id, matiere.id).length === 0 ? 'Aucune note' : 'Voir les notes'"></span>
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
                                            </template> --}}
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
                    typenotes: @json($typenotes),
                    searchTerm: '',
                    semestresEnCours: @json($semestresEnCours),
                    formData: {
                        matiere_id: '',
                        typenote_id: '',
                        note: '',
                    },
                    currentNotesCount: 0,
                    showModal: false,
                    currentNotes: [],
                    currentMatiere: {},
                    currentEleveId: null,
                    filteredEleves: [],
                    filteredClasses: [],
                    filteredNiveaux: [],

                    getNotes(eleveId, matiereId) {
                        const eleve = this.eleves.find(e => e.eleve.id === eleveId);
                        if (!eleve) {
                            console.log('Élève non trouvé');
                            return []; // Retourne un tableau vide si l'élève n'est pas trouvé
                        }

                        // Vérifier si l'élève a des notes et si ces notes sont disponibles
                        if (!eleve.eleve.notes || eleve.eleve.notes.length === 0) {
                            console.log('Aucune note trouvée pour cet élève');
                            return []; // Retourne un tableau vide si aucune note n'est trouvée
                        }

                        // Filtrer les notes pour la matière donnée
                        const notes = eleve.eleve.notes.filter(note => note.matiere_id === matiereId);
                        console.log('Notes trouvées pour l\'élève:', notes);
                        return notes;
                    },

                    openModal(eleveId, matiereId) {
                        this.currentEleveId = eleveId;
                        this.currentMatiere = this.matieres.find(m => m.id === matiereId) || {};

                        // Appel de la méthode getNotes pour récupérer les notes de l'élève et de la matière spécifiés
                        this.currentNotes = this.getNotes(eleveId, matiereId);
                        this.currentNotesCount = this.currentNotes.length; // Calcul du nombre de notes

                        // Affichage du modal
                        this.showModal = true;

                        console.log('Current Notes:', this.currentNotes); // Log pour déboguer
                    },


                    filterEleves() {
                        const term = this.searchTerm.toLowerCase().trim();
                        if (term === '') {
                            this.filteredEleves = this.eleves;
                        } else {
                            this.filteredEleves = this.eleves.filter(eleve =>
                                eleve.eleve.nom.toLowerCase().includes(term) ||
                                eleve.eleve.prenom.toLowerCase().includes(term)
                            );
                        }
                    },


                    init() {
                        // Par exemple, initialiser les élèves filtrés avec tous les élèves au départ
                        this.filteredEleves = this.eleves;

                        // Vous pouvez également appeler d'autres méthodes d'initialisation ici
                        this.filterEleves(); // Exemple : préfiltrer les élèves
                    },


                    closeModal() {
                        this.isModalOpen = false;
                        this.currentNotes = [];
                        this.currentMatiere = {};
                        this.showModal = false;
                    },

                    async submitForm() {
                        this.isLoading = true;

                        // Validation des champs du formulaire
                        if (!this.formData.matiere_id) {
                            Swal.fire({
                                icon: 'error',
                                title: 'La matière est requise.',
                            });
                            this.isLoading = false;
                            return;
                        }

                        if (!this.formData.typenote_id) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Le type de note est requis.',
                            });
                            this.isLoading = false;
                            return;
                        }

                        if (!this.formData.note || this.formData.note.trim() === '') {
                            Swal.fire({
                                icon: 'error',
                                title: 'La note est requise.',
                            });
                            this.isLoading = false;
                            return;
                        }

                        // Créer une instance de FormData
                        const formData = new FormData();
                        formData.append('matiere_id', this.formData.matiere_id);
                        formData.append('typenote_id', this.formData.typenote_id);
                        formData.append('note', this.formData.note);
                        formData.append('eleve_id', this.currentEleveId);

                        try {
                            const response = await fetch('{{ route('notes.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: formData,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                const note = data.note;

                                if (note) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Note enregistrée avec succès.',
                                        showConfirmButton: false,
                                        timer: 1500,
                                    });

                                    // Ajouter la note à l'élève et à la matière concernée
                                    const eleve = this.eleves.find(e => e.eleve.id === this.currentEleveId);
                                    if (eleve) {
                                        // Mettre à jour les notes de l'élève
                                        eleve.eleve.notes.push(note);

                                        // Mettre à jour les notes dans le tableau de l'interface
                                        this.currentNotes.push(note); // Ajouter la nouvelle note à l'affichage
                                    }

                                    // Pour déclencher un rafraîchissement de l'interface, vous pouvez faire ceci :
                                    this.$nextTick(() => {

                                        alert('sss');
                                        this.eleves = [...this.eleves]; // Cela va forcer la réactivité
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur lors de l\'enregistrement de la note.',
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur serveur.',
                            });
                        } finally {
                            this.isLoading = false;
                        }
                    }
                };
            }
        </script>

    @endsection
