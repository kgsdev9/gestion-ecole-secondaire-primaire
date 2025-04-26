@extends('layouts.app')

@section('title', 'Détail du Résultat Examen')

@section('content')
<div class="app-main flex-column flex-row-fluid mt-4">
    <div class="d-flex flex-column flex-column-fluid">

        <!-- Titre -->
        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2">
                        Résultat de l'Examen :
                        <span class="text-primary ms-2">{{ $examen->examen->name ?? 'Non défini' }}</span>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="app-content flex-column-fluid">
            <div class="app-container container-xxl">

                <div class="card">
                    <div class="card-body py-4">

                        <!-- Résumé global -->
                        <div class="mb-6">
                            <h2 class="fw-bold">Synthèse générale</h2>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Année académique :</strong> {{ $examen->anneeAcademique->name ?? '-' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Nombre total de participants :</strong> {{ $examen->nb_total_participant }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Nombre d'admis :</strong> {{ $examen->nb_admis }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Moyenne générale de l'examen :</strong> {{ number_format($examen->moyenne_examen, 2) }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Taux de réussite :</strong> {{ number_format($examen->calculateTauxReussite(), 2) }} %
                                </li>
                                <li class="list-group-item">
                                    <strong>Statut de publication :</strong>
                                    @if ($examen->statut_publication)
                                        <span class="badge bg-success">Publié</span>
                                    @else
                                        <span class="badge bg-warning">Non publié</span>
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <!-- Liste des élèves -->
                        <h2 class="fw-bold mt-10 mb-6">Détail par élève</h2>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th>Nom de l'élève</th>
                                        <th>Nombre total de points</th>
                                        <th>Moyenne</th>
                                        <th>Admis</th>
                                        <th>Mention</th>
                                        <th>Rang</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @forelse ($resultatexamens as $ligne)
                                        <tr>
                                            <td>{{ $ligne->eleve->nom . ' ' . $ligne->eleve->prenom  ?? '-' }}</td>
                                            <td>{{ $ligne->nombre_total_points }}</td>
                                            <td>{{ number_format($ligne->moyenne, 2) }}</td>
                                            <td>
                                                @if ($ligne->admis)
                                                    <span class="badge bg-success">Oui</span>
                                                @else
                                                    <span class="badge bg-danger">Non</span>
                                                @endif
                                            </td>
                                            <td>{{ $ligne->mention ?? '-' }}</td>
                                            <td>{{ $ligne->rang }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun résultat disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Footer avec retour -->
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a href="{{route('examens.resultats.index')}}" class="btn btn-light btn-sm">
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
