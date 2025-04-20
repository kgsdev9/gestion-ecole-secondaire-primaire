@extends('layouts.app')
@section('title', 'Moyennes des élèves')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="affichageMoyennes()" x-init="init()">
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
                        MOYENNES DE L'EXAMEN : <span x-text="examen.nom" class="text-primary ms-2"></span>
                    </h1>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>Élève</th>
                                    <template x-for="matiere in matieres" :key="matiere.id">
                                        <th x-text="matiere.name"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <template x-for="eleve in eleves" :key="eleve.id">
                                    <tr>
                                        <td x-text="eleve.nom + ' ' + eleve.prenom"></td>

                                        <template x-for="matiere in matieres" :key="matiere.id">
                                            <td :class="{
                                                    'text-success fw-bold': parseFloat(notes[eleve.id]?.[matiere.id]) >= 10,
                                                    'text-danger fw-bold': parseFloat(notes[eleve.id]?.[matiere.id]) < 10
                                                }">
                                                <span x-text="notes[eleve.id]?.[matiere.id] ?? '-'"></span>
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

    <script>
        function affichageMoyennes() {
            return {
                examen: @json($examen),
                eleves: @json($eleves),
                matieres: @json($matieres),
                notes: @json($notes),

                init() {
                    console.log('Moyennes chargées', this.notes);
                }
            }
        }
    </script>
@endsection
