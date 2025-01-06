@extends('layouts.app')
@section('title', 'Liste des clients')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="rapportVente()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">

                        <div class="row g-xxl-9">

                            <div class="col-xxl-8">
                                <!--begin::Earnings-->
                                <div class="card  card-xxl-stretch mb-5 mb-xxl-10">
                                    <!--begin::Header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Rapport des ventes </h3>
                                        </div>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div class="card-body pb-0">
                                        <span class="fs-5 fw-semibold text-gray-600 pb-5 d-block">Rapport des ventes calculé
                                            pour la période sélectionnée, incluant les revenus et les performances..</span>

                                        <!--begin::Left Section-->
                                        <div class="d-flex flex-wrap justify-content-between pb-6">
                                            <!--begin::Row-->
                                            <div class="d-flex flex-wrap">
                                                <!--begin::Col-->
                                                <div
                                                    class="border border-dashed border-gray-300 w-300px rounded my-3 p-4 me-6">
                                                    <span class="fs-2x fw-bold text-gray-800 lh-1">
                                                        <span>{{ $listeventesdays }} FCFA </span>
                                                    </span>
                                                    <span class="fs-6 fw-semibold text-gray-500 d-block lh-1 pt-2">Mois en
                                                        cours </span>
                                                </div>
                                                <!--end::Col-->



                                                <!--begin::Col-->
                                                <div
                                                    class="border border-dashed border-gray-300 w-300px rounded my-3 p-4 me-6">
                                                    <span class="fs-2x fw-bold text-gray-800 lh-1">
                                                        <span> {{ $listeventesacademic }} FCFA </span>
                                                    </span>
                                                    <span class="fs-6 fw-semibold text-gray-500 d-block lh-1 pt-2">Année en
                                                        cours </span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->

                                            <!--begin::Form for Select and Date Range-->
                                            <div class="d-flex flex-wrap justify-content-between pb-6 mt-4">
                                                <!--begin::Select-->
                                                <div class="form-group me-4">
                                                    <label for="timeframe" class="form-label">Select Timeframe</label>
                                                    <select id="timeframe" x-model="timeframe" class="form-select">
                                                        <option value="" selected>Choisir un critere</option>
                                                        <option value="month">Mois</option>
                                                        <option value="day">Jour</option>
                                                        <option value="week">Semaine</option>
                                                    </select>
                                                </div>
                                                <!--end::Select-->

                                                <!--begin::Date Inputs-->
                                                <div class="form-group me-4">
                                                    <label for="start-date" class="form-label">Date Début</label>
                                                    <input type="date" x-model="startDate" class="form-control">
                                                </div>
                                                <div class="form-group me-4">
                                                    <label for="end-date" class="form-label">Date Fin</label>
                                                    <input type="date" x-model="endDate" class="form-control">
                                                </div>
                                                <!--end::Date Inputs-->

                                                <!--begin::Generate Button-->
                                                <button @click="searchData()"
                                                    class="btn btn-primary px-6 align-self-center">Générer</button>
                                                <!--end::Generate Button-->
                                            </div>
                                            <!--end::Form-->
                                        </div>

                                        <!--end::Left Section-->
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Earnings-->
                            </div>
                            <!--end::Col-->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function rapportVente() {
            return {
                timeframe: '',
                startDate: '',
                endDate: '', // Date de fin
                isEdite: false,

                async searchData() {
                    const formData = new FormData();
                    formData.append('timeframe', this.timeframe); // Ajoute la période sélectionnée
                    formData.append('start_date', this.startDate); // Ajoute la date de début
                    formData.append('end_date', this.endDate); // Ajoute la date de fin


                   
                    try {
                        const response = await fetch('{{ route('rapport.vente') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            // Créer un lien temporaire pour télécharger le PDF
                            const blob = await response.blob(); // Récupérer le contenu du PDF
                            const url = window.URL.createObjectURL(blob); // Créer un objet URL pour le blob
                            const link = document.createElement('a'); // Créer un lien de téléchargement
                            link.href = url; // Associer l'URL du blob au lien
                            link.download = 'rapport_ventes.pdf'; // Spécifier le nom du fichier
                            link.click(); // Simuler un clic pour démarrer le téléchargement

                            // Afficher un message de succès avec SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Rapport généré avec succès!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de la génération du rapport.',
                                showConfirmButton: true
                            });
                        }
                    } catch (error) {
                        console.error('Erreur réseau :', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Une erreur est survenue.',
                            showConfirmButton: true
                        });
                    }
                }

            };
        }
    </script>
@endsection
