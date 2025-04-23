@extends('layouts.app')
@section('title', 'Moyennes des élèves')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="affichageMoyennes()" x-init="init()">
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 align-items-center my-0">
                        MOYENNES DE L'EXAMEN :
                        <span class="text-primary ms-2" x-text="`${examen.name} (${examen.code})`"></span>
                    </h1>



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
                                            <small class="text-success"
                                                x-text="`1er: ${premiers[matiere.id] ?? '-'}`"></small>
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
                                    <td class="text-success" x-text="moyenneMax !== null ? moyenneMax.toFixed(2) : '-'"></td>


                                </tr>
                                <tr>
                                    <td>Moyenne min</td>
                                    <template x-for="matiere in matieres" :key="matiere.id">
                                        <td></td>
                                    </template>
                                    <td class="text-danger" x-text="moyenneMin !== null ? moyenneMin.toFixed(2) : '-'"></td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a href="{{ route('examens.moyenne.index') }}" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Retourner
                        </a>
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
                moyenneMax: null,
                moyenneMin: null,

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

                            this.moyenneMax = this.moyenneMax === null ? moyenne : Math.max(this.moyenneMax,
                                moyenne);
                            this.moyenneMin = this.moyenneMin === null ? moyenne : Math.min(this.moyenneMin,
                                moyenne);
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
