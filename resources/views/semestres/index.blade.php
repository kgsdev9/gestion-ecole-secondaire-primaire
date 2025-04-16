@extends('layouts.app')
@section('title', 'Gestion des semestres')
@section('content')
    <div class="container py-5" x-data="semestreManagement()" x-init="init()">
        <div class="d-flex justify-content-between mb-4">
            <h3 class="fw-bold">Semestres de l’année académique : {{ $anneacademique->name }}</h3>
            <button class="btn btn-primary" @click="openModal()">
                <i class="fa fa-plus"></i> Ajouter un semestre
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Clôture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(semestre, index) in semestres" :key="semestre.id">
                    <tr>
                        <td x-text="semestre.name"></td>
                        <td x-text="formatDate(semestre.date_debut)"></td>
                        <td x-text="formatDate(semestre.date_fin)"></td>
                        <td>
                            <span :class="semestre.cloture ? 'badge bg-success' : 'badge bg-warning'">
                                <i :class="semestre.cloture ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                                <span x-text="semestre.cloture ? 'Clôturé' : 'Ouvert'"></span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-secondary" @click="toggleCloture(semestre)">
                                <i class="fa" :class="semestre.cloture ? 'fa-unlock' : 'fa-lock'"></i>
                                <span x-text="semestre.cloture ? 'Déclôturer' : 'Clôturer'"></span>
                            </button>
                            <button class="btn btn-danger btn-sm" @click="deleteSemestre(semestre.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- Modal -->
        <template x-if="showModal">
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajout de semestre</h5>
                            <button class="btn-close" @click="closeModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="addSemestre()">
                                <div class="mb-3">
                                    <label>Libellé</label>
                                    <input type="text" class="form-control" x-model="form.name" required>
                                </div>
                                <div class="mb-3">
                                    <label>Date début</label>
                                    <input type="date" class="form-control" x-model="form.date_debut" required>
                                </div>
                                <div class="mb-3">
                                    <label>Date fin</label>
                                    <input type="date" class="form-control" x-model="form.date_fin" required>
                                </div>
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function semestreManagement() {
            return {
                semestres: @json($semestres),
                showModal: false,
                form: {
                    name: '',
                    date_debut: '',
                    date_fin: '',
                },
                anneeAcademiqueId: {{ $anneacademique->id }},

                init() {},

                formatDate(date) {
                    return new Date(date).toLocaleDateString('fr-FR');
                },

                openModal() {
                    this.form = {
                        name: '',
                        date_debut: '',
                        date_fin: '',
                    };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },

                async addSemestre() {
                    const response = await fetch('{{ route('semestre.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ...this.form,
                            annee_academique_id: this.anneeAcademiqueId
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.semestres.push(data.semestre);
                        this.closeModal();
                    } else {
                        alert('Erreur lors de l\'ajout.');
                    }
                },

                async deleteSemestre(id) {
                    const confirmed = confirm("Voulez-vous vraiment supprimer ce semestre ?");
                    if (!confirmed) return;

                    try {
                        const url = `{{ route('semestre.destroy', ['id' => '__ID__']) }}`.replace('__ID__', id);

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            // Retirer le semestre du tableau côté front
                            this.semestres = this.semestres.filter(s => s.id !== id);

                            Swal.fire({
                                icon: 'success',
                                title: 'Semestre supprimé',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Échec de la suppression'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur réseau'
                        });
                    }
                },

                async toggleCloture(semestre) {
                    const response = await fetch(`{{ route('semestre.toggleCloture') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: semestre.id
                        })
                    });

                    if (response.ok) {
                        semestre.cloture = !semestre.cloture;
                    } else {
                        alert('Erreur lors du changement de statut.');
                    }
                }
            };
        }
    </script>
@endsection
