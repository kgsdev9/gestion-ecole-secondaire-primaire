@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            Tableau de bord {{ Auth::user()->name }} </h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <!--begin::Item-->

                            <li class="breadcrumb-item text-muted">
                                <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Dashboards</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>

                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Row-->
                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                        <!--begin::Col-->
                        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                            <!--begin::Card widget 20-->
                            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                                style="background-color: #3E97FF;background-image:url('assets/media/svg/shapes/widget-bg-1.png')">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Amount-->
                                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"></span>
                                        <!--end::Amount-->
                                        <!--begin::Subtitle-->
                                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total des ventes de
                                            l'année
                                        </span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex align-items-end pt-0">
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div
                                            class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                            <span>Objectif financier de l'année </span>
                                            <span>{{ date('y') }}</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                                            <div class="bg-white rounded h-8px" role="progressbar" style="width: 72%;"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <!--end::Progress-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 20-->
                            <!--begin::List widget 26-->
                            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <h3 class="card-title text-gray-800 fw-bold">Raccourci des modules</h3>

                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body pt-5">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <a href="{{ route('clients.index') }}"
                                            class="text-primary fw-semibold fs-6 me-2">Gestion eleves
                                        </a>
                                        <!--end::Section-->
                                        <!--begin::Action-->
                                        <button type="button"
                                            class="btn btn-icon btn-sm h-auto btn-color-gray-400 btn-active-color-primary justify-content-end">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr095.svg-->
                                            <span class="svg-icon svg-icon-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3"
                                                        d="M4.7 17.3V7.7C4.7 6.59543 5.59543 5.7 6.7 5.7H9.8C10.2694 5.7 10.65 5.31944 10.65 4.85C10.65 4.38056 10.2694 4 9.8 4H5C3.89543 4 3 4.89543 3 6V19C3 20.1046 3.89543 21 5 21H18C19.1046 21 20 20.1046 20 19V14.2C20 13.7306 19.6194 13.35 19.15 13.35C18.6806 13.35 18.3 13.7306 18.3 14.2V17.3C18.3 18.4046 17.4046 19.3 16.3 19.3H6.7C5.59543 19.3 4.7 18.4046 4.7 17.3Z"
                                                        fill="currentColor" />
                                                    <rect x="21.9497" y="3.46448" width="13" height="2"
                                                        rx="1" transform="rotate(135 21.9497 3.46448)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M19.8284 4.97161L19.8284 9.93937C19.8284 10.5252 20.3033 11 20.8891 11C21.4749 11 21.9497 10.5252 21.9497 9.93937L21.9497 3.05029C21.9497 2.498 21.502 2.05028 20.9497 2.05028L14.0607 2.05027C13.4749 2.05027 13 2.52514 13 3.11094C13 3.69673 13.4749 4.17161 14.0607 4.17161L19.0284 4.17161C19.4702 4.17161 19.8284 4.52978 19.8284 4.97161Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--end::Action-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>
                                    <!--end::Separator-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <a href="{{ route('ventes.index') }}" class="text-primary fw-semibold fs-6 me-2">
                                            Gestion des ventes </a>
                                        <!--end::Section-->
                                        <!--begin::Action-->
                                        <button type="button"
                                            class="btn btn-icon btn-sm h-auto btn-color-gray-400 btn-active-color-primary justify-content-end">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr095.svg-->
                                            <span class="svg-icon svg-icon-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3"
                                                        d="M4.7 17.3V7.7C4.7 6.59543 5.59543 5.7 6.7 5.7H9.8C10.2694 5.7 10.65 5.31944 10.65 4.85C10.65 4.38056 10.2694 4 9.8 4H5C3.89543 4 3 4.89543 3 6V19C3 20.1046 3.89543 21 5 21H18C19.1046 21 20 20.1046 20 19V14.2C20 13.7306 19.6194 13.35 19.15 13.35C18.6806 13.35 18.3 13.7306 18.3 14.2V17.3C18.3 18.4046 17.4046 19.3 16.3 19.3H6.7C5.59543 19.3 4.7 18.4046 4.7 17.3Z"
                                                        fill="currentColor" />
                                                    <rect x="21.9497" y="3.46448" width="13" height="2"
                                                        rx="1" transform="rotate(135 21.9497 3.46448)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M19.8284 4.97161L19.8284 9.93937C19.8284 10.5252 20.3033 11 20.8891 11C21.4749 11 21.9497 10.5252 21.9497 9.93937L21.9497 3.05029C21.9497 2.498 21.502 2.05028 20.9497 2.05028L14.0607 2.05027C13.4749 2.05027 13 2.52514 13 3.11094C13 3.69673 13.4749 4.17161 14.0607 4.17161L19.0284 4.17161C19.4702 4.17161 19.8284 4.52978 19.8284 4.97161Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--end::Action-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>
                                    <!--end::Separator-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <a href="{{ route('factures.index') }}"
                                            class="text-primary fw-semibold fs-6 me-2">Gestion des versments </a>
                                        <!--end::Section-->
                                        <!--begin::Action-->
                                        <button type="button"
                                            class="btn btn-icon btn-sm h-auto btn-color-gray-400 btn-active-color-primary justify-content-end">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr095.svg-->
                                            <span class="svg-icon svg-icon-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3"
                                                        d="M4.7 17.3V7.7C4.7 6.59543 5.59543 5.7 6.7 5.7H9.8C10.2694 5.7 10.65 5.31944 10.65 4.85C10.65 4.38056 10.2694 4 9.8 4H5C3.89543 4 3 4.89543 3 6V19C3 20.1046 3.89543 21 5 21H18C19.1046 21 20 20.1046 20 19V14.2C20 13.7306 19.6194 13.35 19.15 13.35C18.6806 13.35 18.3 13.7306 18.3 14.2V17.3C18.3 18.4046 17.4046 19.3 16.3 19.3H6.7C5.59543 19.3 4.7 18.4046 4.7 17.3Z"
                                                        fill="currentColor" />
                                                    <rect x="21.9497" y="3.46448" width="13" height="2"
                                                        rx="1" transform="rotate(135 21.9497 3.46448)"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M19.8284 4.97161L19.8284 9.93937C19.8284 10.5252 20.3033 11 20.8891 11C21.4749 11 21.9497 10.5252 21.9497 9.93937L21.9497 3.05029C21.9497 2.498 21.502 2.05028 20.9497 2.05028L14.0607 2.05027C13.4749 2.05027 13 2.52514 13 3.11094C13 3.69673 13.4749 4.17161 14.0607 4.17161L19.0284 4.17161C19.4702 4.17161 19.8284 4.52978 19.8284 4.97161Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--end::Action-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::LIst widget 26-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        {{--  <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                            <!--begin::Card widget 17-->
                            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">

                                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">
                                                {{ $countlisterecentesfactures }} </span>
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Total des factures (FCFA )
                                        </span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">

                                    <div class="d-flex flex-column content-justify-center flex-row-fluid">

                                        <div class="d-flex fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Total jour </div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end">{{ $ventesJour }}</div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Label-->
                                        <div class="d-flex fw-semibold align-items-center my-3">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Total semaine</div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end">{{ $ventesSemaine }}</div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Label-->
                                        <div class="d-flex fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-3px rounded-2 me-3"
                                                style="background-color: #E4E6EF"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Total mois </div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end">{{ $ventesMois }} </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Labels-->
                                </div>
                                <!--end::Card body-->
                            </div>



                            <div class="card card-flush h-lg-50">
                                <div class="card-header pt-5">
                                    <h3 class="card-title text-gray-800">Vos Activités </h3>
                                </div>

                                <div class="card-body pt-5">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Total Client </div>
                                        <!--end::Section-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex align-items-senter">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr094.svg-->
                                            <span class="svg-icon svg-icon-2 svg-icon-success me-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="16.9497" y="8.46448" width="13"
                                                        height="2" rx="1"
                                                        transform="rotate(135 16.9497 8.46448)" fill="currentColor" />
                                                    <path
                                                        d="M14.8284 9.97157L14.8284 15.8891C14.8284 16.4749 15.3033 16.9497 15.8891 16.9497C16.4749 16.9497 16.9497 16.4749 16.9497 15.8891L16.9497 8.05025C16.9497 7.49797 16.502 7.05025 15.9497 7.05025L8.11091 7.05025C7.52512 7.05025 7.05025 7.52513 7.05025 8.11091C7.05025 8.6967 7.52512 9.17157 8.11091 9.17157L14.0284 9.17157C14.4703 9.17157 14.8284 9.52975 14.8284 9.97157Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>

                                            <span class="text-gray-900 fw-bolder fs-6">{{ $counCclient }}</span>

                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>
                                    <!--end::Separator-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Total Factures Ventes </div>
                                        <!--end::Section-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex align-items-senter">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr093.svg-->
                                            <span class="svg-icon svg-icon-2 svg-icon-danger me-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="7.05026" y="15.5355" width="13"
                                                        height="2" rx="1"
                                                        transform="rotate(-45 7.05026 15.5355)" fill="currentColor" />
                                                    <path
                                                        d="M9.17158 14.0284L9.17158 8.11091C9.17158 7.52513 8.6967 7.05025 8.11092 7.05025C7.52513 7.05025 7.05026 7.52512 7.05026 8.11091L7.05026 15.9497C7.05026 16.502 7.49797 16.9497 8.05026 16.9497L15.8891 16.9497C16.4749 16.9497 16.9498 16.4749 16.9498 15.8891C16.9498 15.3033 16.4749 14.8284 15.8891 14.8284L9.97158 14.8284C9.52975 14.8284 9.17158 14.4703 9.17158 14.0284Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <!--begin::Number-->
                                            <span class="text-gray-900 fw-bolder fs-6">{{ $counFaturesVentes }}</span>
                                            <!--end::Number-->
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>

                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::LIst widget 25-->
                        </div>  --}}
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xxl-6">
                            <div class="card card-flush h-md-100">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-800">Liste des etudiants par année </span>
                                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Graphique des rapports</span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="ventesChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

                        <div class="col-xl-12">
                            <!--begin::Table widget 14-->
                            <div class="card card-flush h-md-100">
                                <!--begin::Header-->
                                <div class="card-header pt-7">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-800">Liste des récentes versements effectutés
                                        </span>
                                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Les versements
                                        </span>
                                    </h3>
                                    <!--end::Title-->
                                    <!--begin::Toolbar-->
                                    <div class="card-toolbar">
                                        <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-light">Consulter </a>
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                {{--  <div class="card-body pt-6">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                    <th class="p-0 pb-3 min-w-175px text-start">CODE FACTURE</th>
                                                    <th class="p-0 pb-3 min-w-100px text-end">Mode réglement </th>
                                                    <th class="p-0 pb-3 min-w-175px text-end pe-12">MONTANT TTC</th>
                                                    <th class="p-0 pb-3 w-125px text-end pe-7">Date</th>

                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                @foreach ($listerecentesfactures as $facture)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">

                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="#"
                                                                        class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                                        {{ $facture->numvente }}</a>
                                                                    <span class="text-gray-400 fw-semibold d-block fs-7">
                                                                        {{ $facture->nom }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span
                                                                class="text-gray-600 fw-bold fs-6">{{ $facture->modereglement->libellemodereglement ?? '' }}</span>
                                                        </td>

                                                        <td class="text-end pe-12">
                                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">
                                                                {{ $facture->montantttc }}</span>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            {{ $facture->created_at->format('d/m/y') }}


                                                        </td>

                                                    </tr>
                                                @endforeach





                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>  --}}
                                <!--end: Card Body-->
                            </div>
                            <!--end::Table widget 14-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
        <!--begin::Footer-->

        <!--end::Footer-->
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{--  <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Récupérer les données du backend
            const labels = @json($labels); // Mois
            const data = @json($data); // Totaux

            // Configuration du graphique
            const ctx = document.getElementById('ventesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels, // Noms des mois
                    datasets: [{
                        label: 'Évolution des ventes (en TTC)',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)', // Bleu
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Bleu clair
                        borderWidth: 3,
                        tension: 0.4, // Courbe lissée
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#6c757d',
                                font: {
                                    size: 14,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(54, 162, 235, 0.9)',
                            titleFont: {
                                size: 16,
                                family: "'Poppins', sans-serif"
                            },
                            bodyFont: {
                                size: 14,
                                family: "'Poppins', sans-serif"
                            },
                            padding: 10,
                            borderWidth: 1,
                            borderColor: '#ffffff',
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Mois',
                                color: '#6c757d',
                                font: {
                                    size: 14,
                                    family: "'Poppins', sans-serif"
                                }
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total des ventes (TTC)',
                                color: '#6c757d',
                                font: {
                                    size: 14,
                                    family: "'Poppins', sans-serif"
                                }
                            },
                            ticks: {
                                beginAtZero: true,
                                color: '#6c757d',
                                font: {
                                    size: 12,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>  --}}
@endpush
