@extends('layouts.app')

@section('title', 'Configuration Emploi du Temps')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="emploiDuTemps()">
        <div class="app-container container-xxl">
            <div class="card ">
                <div class="card-header mt-4">
                    <h3 class="fw-bold">Emploi du temps - Classe : {{ $classe->name }}</h3>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <template x-if="emplois.length === 0">
                            <div class="text-center text-muted py-10">Aucune ligne d'emploi du temps.</div>
                        </template>

                        <template x-if="emplois.length > 0">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th>Matière</th>
                                        <th>Jour</th>
                                        <th>Heure de début</th>
                                        <th>Heure de fin</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    <template x-for="(emploi, index) in emplois" :key="index">
                                        <tr>
                                            <td>
                                                <select class="form-select" x-model="emploi.matiere_id" required>
                                                    <option value="">Sélectionner une matière</option>
                                                    <template x-for="matiere in matieres" :key="matiere.id">
                                                        <option :value="matiere.id" x-text="matiere.name"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" x-model="emploi.jour_id" required>
                                                    <option value="">Sélectionner un jour</option>
                                                    <template x-for="jour in jours" :key="jour.id">
                                                        <option :value="jour.id" x-text="jour.name"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td><input type="time" class="form-control" x-model="emploi.heure_debut"
                                                    required></td>
                                            <td><input type="time" class="form-control" x-model="emploi.heure_fin"
                                                    required></td>
                                            <td class="text-end">
                                                <button @click="deleteRow(index)" class="btn btn-danger btn-sm mx-2">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-light btn-sm" @click="addRow()">
                        <i class="fa fa-plus me-1"></i> Ajouter une ligne
                    </button>

                    <button class="btn btn-primary btn-sm" @click="submitEmploiDuTemps()">
                        <i class="fa fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function emploiDuTemps() {
            return {
                emplois: @json($emplois),
                matieres: @json($matieres),
                classe: @json($classe),
                jours: @json($jours),

                addRow() {
                    this.emplois.push({
                        id: null,
                        matiere_id: '',
                        jour_id: '',
                        heure_debut: '',
                        heure_fin: '',
                    });
                },

                deleteRow(index) {
                    this.emplois.splice(index, 1);
                },

                submitEmploiDuTemps() {
                    fetch('{{ route('administration.emplois-du-temps.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                classe_id: this.classe.id,
                                emplois: this.emplois
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error("Échec de la requête");
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Enregistré avec succès',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur est survenue.',
                            });
                            console.error(error);
                        });
                }

            };
        }
    </script>
@endsection
