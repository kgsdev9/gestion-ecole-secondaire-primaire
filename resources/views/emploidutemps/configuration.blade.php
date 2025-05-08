@extends('layouts.app')

@section('title', 'Configuration Emploi du Temps')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="emploiDuTemps()" x-init="init()">
        <div class="app-container container-xxl">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="emploiTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="editionTab" data-bs-toggle="tab" href="#edition" role="tab"
                        aria-controls="edition" aria-selected="true">Édition</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="aperçuTab" data-bs-toggle="tab" href="#aperçu" role="tab"
                        aria-controls="aperçu" aria-selected="false">Aperçu</a>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <!-- Première table : édition -->
                <div class="tab-pane fade show active" id="edition" role="tabpanel" aria-labelledby="editionTab">
                    <div class="card">
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
                                                                <option :value="matiere.id" x-text="matiere.name">
                                                                </option>
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
                                                    <td><input type="time" class="form-control"
                                                            x-model="emploi.heure_debut" required></td>
                                                    <td><input type="time" class="form-control"
                                                            x-model="emploi.heure_fin" required></td>
                                                    <td class="text-end">
                                                        <button @click="deleteRow(index)"
                                                            class="btn btn-danger btn-sm mx-2">
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

                <div class="tab-pane fade" id="aperçu" role="tabpanel" aria-labelledby="aperçuTab">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h3 class="fw-bold">Aperçu de l'emploi du temps</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>Heure / Jour</th>
                                        <template x-for="jour in jours" :key="jour.id">
                                            <th x-text="jour.name"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Générer les horaires de 8h à 18h -->
                                    <template x-for="heure in generateHeures('08:00', '18:00', 60)" :key="heure">
                                        <tr>
                                            <td x-text="heure"></td>
                                            <!-- Afficher les matières pour chaque jour et chaque heure -->
                                            <template x-for="jour in jours" :key="jour.id">
                                                <td>
                                                    <!-- Vérifier si l'emploi correspond à l'heure et au jour -->
                                                    <template x-for="emploi in emplois" :key="emploi.id">
                                                        <template
                                                            x-if="emploi.heure_debut === heure && emploi.jour_id == jour.id">
                                                            <span class="badge bg-primary"
                                                                x-text="getMatiereName(emploi.matiere)"></span>
                                                        </template>
                                                    </template>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
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

                // Fonction pour obtenir le nom de la matière
                getMatiereName(matiere) {
                    alert(matiere.name);  // Afficher l'alerte avec le nom de la matière
                    return matiere ? matiere.name : 'Non définie';
                },

                // Fonction d'initialisation
                init() {
                    if (this.matieres.length > 0) {
                        this.getMatiereName(this.matieres[0]); // Appeler la fonction avec une matière pour tester
                    }
                },

                generateHeures(start, end, step) {
                    let hours = [];
                    let current = moment(start, "HH:mm");
                    let stop = moment(end, "HH:mm");

                    while (current <= stop) {
                        hours.push(current.format("HH:mm"));
                        current.add(step, 'minutes');
                    }
                    return hours;
                },

                // Ajoute une nouvelle ligne d'emploi du temps
                addRow() {
                    this.emplois.push({
                        id: null,
                        matiere_id: '',
                        jour_id: '',
                        heure_debut: '',
                        heure_fin: '',
                    });
                },

                // Supprime une ligne
                deleteRow(index) {
                    this.emplois.splice(index, 1);
                },

                // Enregistrer l'emploi du temps
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

                            // Mettre à jour les emplois avec les données renvoyées
                            this.emplois = data.emplois;
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
