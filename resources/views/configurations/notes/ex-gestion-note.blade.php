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
                                <div class="card-header border-0 pt-6 d-flex justify-content-between">
                                    <input type="text" class="form-control w-250px" placeholder="Rechercher"
                                        x-model="searchTerm">
                                    <button @click="openModal" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Ajouter Note
                                    </button>
                                </div>

                                <div class="card-body pt-0 pb-5">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed gy-5">
                                            <thead>
                                                <tr>
                                                    <th>Matière</th>
                                                    <template x-for="type in typenotes" :key="type.id">
                                                        <th x-text="type.name"></th>
                                                    </template>
                                                    <th>Moyenne</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="matiere in matieres" :key="matiere.id">
                                                    <tr>
                                                        <td x-text="matiere.name"></td>
                                                        <template x-for="type in typenotes" :key="type.id">
                                                            <td x-text="getNoteValue(matiere.id, type.id) ?? '-'"></td>
                                                        </template>
                                                        <td x-text="getMoyenneParMatiere(matiere.id)"></td>
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
                                                <div class="mb-3">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" x-model="formData.date"
                                                        required>
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
                typenotes: @json($typenotes),
                notes: @json($notes),
                selectedEleve: {},
                searchTerm: '',
                showModal: false,
                formData: {
                    matiere_id: '',
                    typenote_id: '',
                    valeur: '',
                    date: '',
                },

                selectEleve(eleve) {
                    this.selectedEleve = eleve;
                },

                // Afficher la valeur de la note pour un type donné dans une matière
                getNoteValue(matiereId, typenoteId) {
                    const notes = this.notes.filter(n =>
                        n.eleve_id === this.selectedEleve.id &&
                        n.matiere_id === matiereId &&
                        n.typenote_id === typenoteId
                    );

                    if (notes.length === 0) return '-';
                    return notes.map(n => n.note).join(', ');
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
                        typenote_id: '',
                        valeur: '',
                        date: '',
                    };
                },

                async submitNote() {
                    const form = new FormData();
                    form.append('eleve_id', this.selectedEleve.id);
                    form.append('matiere_id', this.formData.matiere_id);
                    form.append('typenote_id', this.formData.typenote_id);
                    form.append('note', this.formData.valeur);
                    form.append('date', this.formData.date);

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
                            Swal.fire("Succès", "Note ajoutée.", "success");
                            alert('ss');
                        } else {
                            Swal.fire("Erreur", "Échec lors de l'ajout.", "error");
                        }
                    } catch (e) {
                        Swal.fire("Erreur serveur", "", "error");
                    }
                }
            };
        }
    </script>

@endsection
