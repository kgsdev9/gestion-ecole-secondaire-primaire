@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="rapportVente()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">

                        <div class="row g-xxl-9">
                            <!--begin::Col-->
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
                                                        <span data-kt-countup="true">{{ $listeventesdays }} FCFA </span>
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
                                                    <select id="timeframe" class="form-select">
                                                        <option value="month">Mois</option>
                                                        <option value="day">Jour</option>
                                                        <option value="week">Semaine</option>
                                                    </select>
                                                </div>
                                                <!--end::Select-->

                                                <!--begin::Date Inputs-->
                                                <div class="form-group me-4">
                                                    <label for="start-date" class="form-label">Date Début</label>
                                                    <input type="date" id="start-date" class="form-control">
                                                </div>
                                                <div class="form-group me-4">
                                                    <label for="end-date" class="form-label">Date Fin</label>
                                                    <input type="date" id="end-date" class="form-control">
                                                </div>
                                                <!--end::Date Inputs-->

                                                <!--begin::Generate Button-->
                                                <a href="#" class="btn btn-primary px-6 align-self-center">Générer</a>
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



@endsection
