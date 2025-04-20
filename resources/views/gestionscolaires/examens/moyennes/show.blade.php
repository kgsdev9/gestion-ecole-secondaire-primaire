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

                    <div class="card-toolbar">
                        {{-- <a :href="`{{ route('examens.managementgrade.print', ['id' => '__ID__']) }}`.replace('__ID__', examen.id)"
                            target="_blank"
                            class="btn btn-secondary btn-sm" title="Imprimer les moyennes">
                            <i class="fa fa-print"></i> Imprimer
                        </a> --}}
                    </div>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>Élève</th>
                                    <template x-for="matiere in matieres" :key="matiere.id">
                                        <th>
                                            <span x-text="matiere.name"></span><br>
                                            <small class="text-success" x-text="`1er: ${premiers[matiere.id] ?? '-'}`"></small>
                                        </th>
                                    </template>
                                    <th>Moyenne Générale</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <template x-for="eleve in eleves" :key="eleve.id">
                                    <tr>
                                        <td x-text="eleve.nom + ' ' + eleve.prenom"></td>

                                        <template x-for="matiere in matieres" :key="matiere.id">
                                            <td :class="getColor(notes[eleve.id]?.[matiere.id])">
                                                <span x-text="notes[eleve.id]?.[matiere.id] ?? '-'"></span>
                                            </td>
                                        </template>

                                        <td :class="getColor(moyennesGenerales[eleve.id])">
                                            <span x-text="moyennesGenerales[eleve.id]?.toFixed(2) ?? '-'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="fw-bold">
                                <tr>
                                    <td>Moyenne max</td>
                                    <template x-for="matiere in matieres" :key="matiere.id">
                                        <td></td>
                                    </template>
                                    <td class="text-success" x-text="moyenneMax.toFixed(2)"></td>
                                </tr>
                                <tr>
                                    <td>Moyenne min</td>
                                    <template x-for="matiere in matieres" :key="matiere.id">
                                        <td></td>
                                    </template>
                                    <td class="text-danger" x-text="moyenneMin.toFixed(2)"></td>
                                </tr>
                            </tfoot>
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
                premiers: @json($premiers), 
                moyennesGenerales: {},
                moyenneMax: 0,
                moyenneMin: 20,

                init() {
                    this.calculerMoyennes();
                },

                calculerMoyennes() {
                    this.eleves.forEach(eleve => {
                        let total = 0;
                        let count = 0;

                        this.matieres.forEach(matiere => {
                            const note = this.notes[eleve.id]?.[matiere.id];
                            if (note !== undefined && note !== null && !isNaN(note)) {
                                total += parseFloat(note);
                                count++;
                            }
                        });

                        if (count > 0) {
                            const moyenne = total / count;
                            this.moyennesGenerales[eleve.id] = moyenne;

                            if (moyenne > this.moyenneMax) this.moyenneMax = moyenne;
                            if (moyenne < this.moyenneMin) this.moyenneMin = moyenne;
                        }
                    });
                },

                getColor(note) {
                    if (note === undefined || note === null || note === '-') return '';
                    return parseFloat(note) >= 10 ? 'text-success' : 'text-danger';
                }
            }
        }
    </script>
@endsection
