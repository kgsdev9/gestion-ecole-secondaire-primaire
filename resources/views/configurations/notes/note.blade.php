@extends('layouts.app')
@section('title', 'Gestion des notes')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="noteManager()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading text-gray-900 fw-bold fs-3">GESTION DES NOTES</h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="d-flex flex-column flex-xl-row">
                        <!-- Élève -->
                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body pt-15">
                                    <div class="d-flex flex-center flex-column mb-5">
                                        <div class="symbol symbol-150px symbol-circle mb-7">
                                            <img src="{{ asset('avatar.png') }}" alt="image">
                                        </div>
                                        <a href="#" class="fs-3 fw-bold" x-text="selectedEleve.nom"></a>
                                        <a href="#" class="fs-5 text-muted" x-text="selectedEleve.email"></a>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle w-100" type="button"
                                            data-bs-toggle="dropdown">
                                            <span x-text="selectedEleve.nom || 'Sélectionner un élève'"></span>
                                        </button>
                                        <ul class="dropdown-menu w-100">
                                            <template x-for="eleve in eleves" :key="eleve.id">
                                                <li class="dropdown-item" @click="selectEleve(eleve)">
                                                    <strong x-text="eleve.nom"></strong> - <span
                                                        x-text="eleve.matricule"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des notes par matière -->
                        <div class="flex-lg-row-fluid ms-lg-15">
                            <div class="card pt-4 mb-6">
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
                                            <button @click="openModal()"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                                <i class="fa fa-add"></i> Création
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body pt-0 pb-5">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed gy-5">
                                            <thead>
                                                <tr>
                                                    <th>Matière</th>
                                                    <th>Notes</th> <!-- Nouvelle colonne unique pour les notes -->
                                                    <th>Moyenne</th>
                                                    <th>Action</th> <!-- Colonne Action -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="matiere in matieres" :key="matiere.id">
                                                    <tr>
                                                        <td x-text="matiere.name"></td>
                                                        <td>
                                                            <!-- Affichage des notes sur une seule ligne avec boutons supprimer -->
                                                            <template x-if="getNotesForMatiere(matiere.id).length > 0">
                                                                <div class="d-flex flex-wrap">
                                                                    <template
                                                                        x-for="(note, index) in getNotesForMatiere(matiere.id)"
                                                                        :key="index">
                                                                        <div class="d-flex align-items-center me-2 mb-2">
                                                                            <span x-text="note" class="me-2"></span>
                                                                            <!-- Bouton supprimer pour chaque note -->
                                                                            <button
                                                                                @click="deleteNoteFromMatiere(matiere.id, note)"
                                                                                class="btn btn-danger btn-sm">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                            <template x-if="getNotesForMatiere(matiere.id).length === 0">
                                                                -
                                                            </template>
                                                        </td>
                                                        <td x-text="getMoyenneParMatiere(matiere.id)"></td>
                                                        <td>
                                                            <!-- Bouton pour valider les notes -->
                                                            <template x-if="getNotesForMatiere(matiere.id).length > 0">
                                                                <button @click="validateNotes(matiere.id)"
                                                                    class="btn btn-success btn-sm">
                                                                    <i class="fa fa-check"></i> Valider
                                                                </button>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal ajout note -->
                        <template x-if="showModal">
                            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ajouter Note</h5>
                                            <button type="button" class="btn-close" @click="closeModal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form @submit.prevent="submitNote">
                                                <div class="mb-3">
                                                    <label>Matière</label>
                                                    <select class="form-control" x-model="formData.matiere_id" required>
                                                        <option value="">Sélectionner une matière</option>
                                                        <template x-for="matiere in matieres" :key="matiere.id">
                                                            <option :value="matiere.id" x-text="matiere.name"></option>
                                                        </template>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Type de note</label>
                                                    <select class="form-control" x-model="formData.typenote_id" required>
                                                        <option value="">Sélectionner un type</option>
                                                        <template x-for="type in typenotes" :key="type.id">
                                                            <option :value="type.id" x-text="type.name"></option>
                                                        </template>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Note</label>
                                                    <input type="number" class="form-control" x-model="formData.valeur"
                                                        min="0" max="20" required>
                                                </div>

                                                <button type="submit" class="btn btn-success">Enregistrer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Fin modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function noteManager() {
            return {
                eleves: @json($students),
                matieres: @json($matieres),
                notes: @json($notes),
                typenotes: @json($typenotes),
                selectedEleve: {},
                searchTerm: '',
                showModal: false,
                formData: {
                    matiere_id: '',
                    valeur: '',
                },

                selectEleve(eleve) {
                    this.selectedEleve = eleve;
                },

                // Récupérer les notes pour une matière spécifique et afficher en ligne
                getNotesForMatiere(matiereId) {
                    return this.notes
                        .filter(n => n.matiere_id === matiereId && n.eleve_id === this.selectedEleve.id)
                        .map(n => n.note);
                },

                getMoyenneParMatiere(matiereId) {
                    const notes = this.notes.filter(n =>
                        n.eleve_id === this.selectedEleve.id &&
                        n.matiere_id === matiereId
                    );
                    if (notes.length === 0) return '-';
                    const total = notes.reduce((sum, n) => sum + parseFloat(n.note || 0), 0);
                    return (total / notes.length).toFixed(2);
                },

                openModal() {
                    if (!this.selectedEleve.id) {
                        alert("Veuillez sélectionner un élève.");
                        return;
                    }
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    this.formData = {
                        matiere_id: '',
                        valeur: '',
                    };
                },

                async submitNote() {
                    const form = new FormData();
                    form.append('eleve_id', this.selectedEleve.id);
                    form.append('matiere_id', this.formData.matiere_id);
                    form.append('note', this.formData.valeur);
                    form.append('typenote_id', this.formData.typenote_id);


                    try {
                        const response = await fetch('{{ route('configurationnote.create.gestion.note') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: form
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.notes.push(data.note);
                            this.closeModal();
                            // window.history.replaceState(null, '', '{{ request()->url() }}');
                            Swal.fire("Succès", "Note ajoutée.", "success");
                            window.location.assign(window.location.href);
                        } else {
                            Swal.fire("Erreur", "Échec lors de l'ajout.", "error");
                        }
                    } catch (e) {
                        Swal.fire("Erreur serveur", "", "error");
                    }
                },

                // Méthode pour supprimer une note spécifique d'une matière
                async deleteNoteFromMatiere(matiereId, note) {
                    if (!confirm("Êtes-vous sûr de vouloir supprimer cette note ?")) return;

                    try {
                        // Trouver l'ID de la note à supprimer
                        const noteToDelete = this.notes.find(n => n.matiere_id === matiereId && n.note === note && n
                            .eleve_id === this.selectedEleve.id);
                        if (!noteToDelete) return;

                        const response = await fetch(
                            `{{ route('configurationnote.delete.gestion.note', '') }}/${noteToDelete.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                        if (response.ok) {
                            this.notes = this.notes.filter(n => n.id !== noteToDelete.id);
                            Swal.fire("Succès", "Note supprimée.", "success");
                        } else {
                            Swal.fire("Erreur", "Échec lors de la suppression.", "error");
                        }
                    } catch (e) {
                        Swal.fire("Erreur serveur", "", "error");
                    }
                },

                // Méthode pour valider les notes d'une matière
                async validateNotes(matiereId) {
                    const notesForMatiere = this.getNotesForMatiere(matiereId);
                    if (notesForMatiere.length === 0) {
                        Swal.fire("Aucune note", "Il n'y a pas de notes à valider pour cette matière.", "warning");
                        return;
                    }

                    try {
                        const response = await fetch(
                            `{{ route('configurationnote.validate.matiere', '') }}/${matiereId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    eleve_id: this.selectedEleve.id,
                                    matiere_id: matiereId,
                                    notes: notesForMatiere,
                                })
                            });

                        if (response.ok) {
                            Swal.fire("Succès", "Notes validées avec succès.", "success");
                        } else {
                            Swal.fire("Erreur", "Échec de la validation des notes.", "error");
                        }
                    } catch (e) {
                        Swal.fire("Erreur serveur", "", "error");
                    }
                }
            };
        }
    </script>
@endsection
