@extends('layouts.app')
@section('title', 'Gestion des notes')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="notesManager()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES EMPLOIS DU TEMPS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Emploi du temps </li>
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
                                        placeholder="Rechercher un cours " x-model="searchTerm" @input="filterEleves" />

                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select @change="filterClasse" class="form-select form-select-sm"
                                            data-live-search="true">
                                            <option value="">Toutes les classes</option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.classe.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <button @click="printProducts" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        @click="showModal = true">
                                        <i class="fa fa-add"></i> Création
                                    </button>

                                </div>
                            </div>
                        </div>

                        <div class="container mt-4">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="border: 1px solid #ccc; text-align: center;">
                                    <thead style="background-color: #f5f5f5;">
                                        <tr>
                                            <th style="width: 15%; font-weight: bold;">Heure</th>
                                            @foreach ($jours as $jour)
                                                <th style="font-weight: bold;">{{ $jour }}</th>

                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($heures as $heureDebut)
                                            <tr>
                                                <td style="font-weight: bold; vertical-align: middle;">
                                                    @php
                                                        // Récupérer l'heure de fin associée à l'heure de début
                                                        $heureFin = null;
                                                        // Ici on change la variable $jours en $jourName pour éviter la confusion
                                                        foreach ($emploisParJourEtHeure as $jourName => $heures) {
                                                            if (isset($heures[$heureDebut])) {
                                                                $heureFin = $heures[$heureDebut][0]['heure_fin']; // Prendre la première fin de l'heure pour l'exemple
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    {{ \Carbon\Carbon::parse($heureDebut)->format('H:i') }} -
                                                    @if ($heureFin)
                                                        {{ \Carbon\Carbon::parse($heureFin)->format('H:i') }}
                                                    @else
                                                        Fin
                                                    @endif
                                                </td>

                                                <!-- Colonnes des jours -->
                                                @foreach ($jours as $jour)
                                                    <td style="vertical-align: middle; background-color: #fdfdfd;">
                                                        @php
                                                            // Vérifier si des emplois existent pour cette heure et ce jour
                                                            $emploisDuJour =
                                                                $emploisParJourEtHeure[$jour][$heureDebut] ?? [];
                                                        @endphp
                                                        @if (!empty($emploisDuJour))
                                                            @foreach ($emploisDuJour as $emploi)
                                                                <div style="padding: 5px; ">
                                                                    <strong>{{ $emploi['matiere'] }}</strong><br>
                                                                    <span>{{ $emploi['classe'] }}</span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span style="color: #ccc;">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>



                            </div>
                        </div>


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

                    </div>
                </div>
            </div>
        </div>

        <script>
            function notesManager() {
                return {
                    eleves: [],
                    searchTerm: '',
                    classes: @json($classes),
                    formData: {
                        matiere_id: '',
                        typenote_id: '',
                        note: '',
                    },

                    init() {

                    },
                    currentNotesCount: 0,
                    showModal: false,
                    currentNotes: [], // Initialiser à un tableau vide
                    currentMatiere: {},
                    currentEleveId: null,
                    filteredEleves: [],
                    filteredClasses: [],
                    filteredNiveaux: [],

                    openModal(eleveId, matiereId) {
                        this.currentEleveId = eleveId;
                        this.currentMatiere = this.matieres.find(m => m.id === matiereId) || {};
                        this.currentNotes = this.getNotes(eleveId, matiereId);
                        this.currentNotesCount = this.currentNotes.length;
                        this.showModal = true;
                    },

                    // Validation et soumission du formulaire
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

                        // Préparation des données à envoyer
                        const formData = new FormData();
                        formData.append('matiere_id', this.formData.matiere_id);
                        formData.append('typenote_id', this.formData.typenote_id);
                        formData.append('note', this.formData.note);
                        formData.append('eleve_id', 11); // Test avec ID 11

                        try {
                            // Soumettre les données au contrôleur avec fetch
                            const response = await fetch('{{ route('notes.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                },
                                body: formData,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                console.log('Note ajoutée:', data);

                                // Mise à jour des notes dans l'élève
                                const updatedEleve = this.eleves.find(eleve => eleve.eleve.id === 11); // Test avec ID 11
                                if (updatedEleve) {
                                    updatedEleve.eleve.notes.push(data.note); // Ajouter la note

                                    // **Forcer Alpine à re-rendre en réinitialisant l'élément élève**
                                    // Nous allons mettre à jour l'ensemble des élèves pour forcer la vue à se rafraîchir
                                    this.eleves = [...this.eleves];

                                    // Réinitialiser filteredEleves pour forcer Alpine.js à mettre à jour la vue
                                    this.filterEleves();

                                    // Réactualiser les notes
                                    this.currentNotes = updatedEleve.eleve.notes.filter(note => note.matiere_id === this
                                        .formData.matiere_id);
                                    this.currentNotesCount = this.currentNotes.length;
                                    console.log('Notes actualisées:', this.currentNotes);
                                }

                                // Rafraîchir la vue
                                this.$nextTick(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Note enregistrée avec succès.',
                                    });

                                    // Fermer le modal
                                    this.closeModal();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur lors de l\'enregistrement de la note.',
                                });
                            }
                        } catch (error) {
                            console.error('Erreur dans le traitement de la requête:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Une erreur est survenue, veuillez réessayer.',
                            });
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    // Fermer le modal
                    closeModal() {
                        this.currentNotes = [];
                        this.currentMatiere = {};
                        this.showModal = false;
                    },

                };
            }
        </script>

    @endsection
