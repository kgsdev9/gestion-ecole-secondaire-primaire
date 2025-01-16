@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2 style="font-family: 'Arial', sans-serif; color: #333;">Emploi du Temps</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" style="border: 1px solid #ccc; text-align: center;">
                <thead style="background-color: #f5f5f5;">
                    <tr>
                        <th style="width: 15%; font-weight: bold;">Heure</th>
                        @foreach ($jours as $jour)
                            <th style="font-weight: bold;">{{ $jour }}</th> <!-- Affichage du nom du jour -->
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($heures as $heureDebut)
                        <tr>
                            <td style="background-color: #f0f8ff; font-weight: bold; vertical-align: middle;">
                                @php
                                    // Récupérer l'heure de fin associée à l'heure de début
                                    $heureFin = null;
                                    // Ici on change la variable $jours en $jourName pour éviter la confusion
                                    foreach ($emploisParJourEtHeure as $jourName => $heures) {
                                        if (isset($heures[$heureDebut])) {
                                            $heureFin = $heures[$heureDebut][0]['heure_fin']; // Prendre la première fin de l'heure pour l'exemple
                                            break;
                                        }
                                    }
                                @endphp
                                {{ \Carbon\Carbon::parse($heureDebut)->format('H:i') }} -
                                @if ($heureFin)
                                    {{ \Carbon\Carbon::parse($heureFin)->format('H:i') }}
                                @else
                                    Fin
                                @endif
                            </td>

                            <!-- Colonnes des jours -->
                            @foreach ($jours as $jour)
                                <td style="vertical-align: middle; background-color: #fdfdfd;">
                                    @php
                                        // Vérifier si des emplois existent pour cette heure et ce jour
                                        $emploisDuJour = $emploisParJourEtHeure[$jour][$heureDebut] ?? [];
                                    @endphp
                                    @if (!empty($emploisDuJour))
                                        @foreach ($emploisDuJour as $emploi)
                                            <div style="padding: 5px; background-color: #e6f7ff; border-radius: 5px; margin-bottom: 5px;">
                                                <strong>{{ $emploi['matiere'] }}</strong><br>
                                                <span>{{ $emploi['classe'] }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #ccc;">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            </table>



        </div>
    </div>
@endsection

<style>
    table.table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
    }

    table.table th,
    table.table td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    table.table th {
        background-color: #add8e6;
        color: #333;
        text-transform: uppercase;
    }

    table.table td div {
        padding: 8px;
        text-align: center;
    }
</style>
