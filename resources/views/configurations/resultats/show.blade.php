@extends('layouts.app')

@section('title', 'Détail du rapport semestriel')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 justify-content-center my-0">
                            Rapport semestriel - {{ $resultatsemestres->semestre->name ?? 'Semestre inconnu' }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card mb-5">
                        <div class="card-body">
                            <h5 class="mb-3">Informations générales :</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Année académique :</strong> {{ $resultatsemestres->anneeacademique->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Niveau :</strong> {{ $resultatsemestres->niveau->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Classe :</strong> {{ $resultatsemestres->classe->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Nombre d'élèves :</strong> {{ $resultatsemestres->nombre_eleves }}</li>
                                <li class="list-group-item"><strong>Moyenne générale :</strong> {{ $resultatsemestres->moyenne_generale }}</li>
                                <li class="list-group-item"><strong>Taux de réussite :</strong> {{ $resultatsemestres->taux_reussite }}%</li>
                                <li class="list-group-item"><strong>Observations :</strong> {{ $resultatsemestres->observations }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body py-4">
                            <h5 class="mb-4">Détails des résultats :</h5>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th>Nom de l’élève</th>
                                            <th>Moyenne</th>
                                            <th>Décision</th>
                                            <th>Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        @forelse ($resultatsemestreslignes as $ligne)
                                            <tr>
                                                <td>{{ $ligne->eleve->nom . ' ' .$ligne->eleve->nom ?? 'Élève inconnu' }}</td>
                                                <td>{{ $ligne->moyenne }}</td>
                                                <td>{{ $ligne->decision ?? '-' }}</td>
                                                <td>{{ $ligne->observation ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Aucune donnée disponible.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <div>
                                <a href="{{route('configurationnote.semestre.index')}}" class="btn btn-light btn-sm">
                                    <i class="fa fa-arrow-left me-1"></i> Retour
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
