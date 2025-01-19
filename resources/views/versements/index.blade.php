@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="versementManager()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES VERSEMENTS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Roles</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">

                    <div class="d-flex flex-column flex-xl-row">
                        <!--begin::Sidebar-->
                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

                            <!--begin::Card-->
                            <div class="card mb-5 mb-xl-8">
                                <!--begin::Card body-->
                                <div class="card-body pt-15">
                                    <!--begin::Summary-->
                                    <div class="d-flex flex-center flex-column mb-5">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-150px symbol-circle mb-7">
                                            <img src="{{ asset('avatar.png') }}" alt="image">
                                        </div>
                                        <!--end::Avatar-->

                                        <!--begin::Name-->
                                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                                            Max Smith </a>
                                        <!--end::Name-->

                                        <!--begin::Email-->
                                        <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                                            max@kt.com </a>
                                        <!--end::Email-->
                                    </div>

                                    <div class="custom-select-container">

                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle w-100" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <template x-if="selectedClient.nom">
                                                    <div class="d-flex align-items-center">
                                                        <img x-bind:src="selectedClient.avatar ||
                                                            'https://via.placeholder.com/40'"
                                                            alt="Avatar" class="rounded-circle me-2"
                                                            width="40" height="40" />
                                                        <div>
                                                            <strong x-text="selectedClient.nom"></strong>
                                                            <br />
                                                            <span class="text-muted"
                                                                x-text="selectedClient.email"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                <span
                                                    x-text="selectedClient.nom ? '' : 'Aucun client sélectionné'"></span>
                                            </button>

                                            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                                <!-- Champ de recherche -->
                                                <li class="px-2 py-1">
                                                    <input type="text" class="form-control"
                                                        placeholder="Rechercher..." x-model="searchQuery" />
                                                </li>

                                                <!-- Liste des clients filtrée -->
                                                <template x-for="client in filteredClients()"
                                                    :key="client.id">
                                                    <li class="dropdown-item d-flex align-items-center"
                                                        @click="updateClientInfo(client.id, client.libtiers, client.email, client.adressepostale, client.telephone, 'https://via.placeholder.com/40')"
                                                        style="cursor: pointer;">
                                                        <img src="https://via.placeholder.com/40" alt="Avatar"
                                                            class="rounded-circle me-2" width="40"
                                                            height="40" />
                                                        <div>
                                                            <strong x-text="client.libtiers"></strong>
                                                            <br />
                                                            <span class="text-muted" x-text="client.email"></span>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>


                                    <!--end::Summary-->

                                    <!--begin::Details toggle-->
                                    <div class="d-flex flex-stack fs-4 py-3">
                                        <div class="fw-bold">
                                            Details
                                        </div>

                                        <!--begin::Badge-->
                                        <div class="badge badge-light-info d-inline">Premium user</div>
                                        <!--begin::Badge-->
                                    </div>
                                    <!--end::Details toggle-->

                                    <div class="separator separator-dashed my-3"></div>

                                    <!--begin::Details content-->
                                    <div class="pb-5 fs-6">
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Account ID</div>
                                        <div class="text-gray-600">ID-45453423</div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Billing Email</div>
                                        <div class="text-gray-600"><a href="#"
                                                class="text-gray-600 text-hover-primary">info@keenthemes.com</a></div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Delivery Address</div>
                                        <div class="text-gray-600">101 Collin Street, <br>Melbourne 3000 VIC<br>Australia
                                        </div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Language</div>
                                        <div class="text-gray-600">English</div>
                                        <!--begin::Details item-->
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">Latest Transaction</div>
                                        <div class="text-gray-600"><a href="/keen/demo1/apps/ecommerce/sales/details.html"
                                                class="text-gray-600 text-hover-primary">#14534</a> </div>
                                        <!--begin::Details item-->
                                    </div>
                                    <!--end::Details content-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Sidebar-->

                        <!--begin::Content-->
                        <div class="flex-lg-row-fluid ms-lg-15">

                            <!--begin:::Tab content-->
                            <div class="tab-content" id="myTabContent">
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade active show" id="kt_ecommerce_customer_overview" role="tabpanel">



                                    <!--begin::Card-->
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <!--begin::Card header-->
                                        <div class="card-header border-0">
                                            <!--begin::Card title-->
                                            <div class="card-title">
                                                <h2>Historique des versements </h2>
                                            </div>
                                            <!--end::Card title-->
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body pt-0 pb-5">
                                            <!--begin::Table-->
                                            <div
                                                class="dt-container dt-bootstrap5 dt-empty-footer">
                                                <div id="" class="table-responsive">
                                                    <table class="table align-middle table-row-dashed gy-5 dataTable"
                                                       style="width: 100%;">
                                                        <colgroup>
                                                            <col style="width: 118.344px;">
                                                            <col  style="width: 100.469px;">
                                                            <col  style="width: 88.1406px;">
                                                            <col style="width: 118.344px;">
                                                            <col  style="width: 160.703px;">
                                                        </colgroup>
                                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                            <tr class="text-start text-muted text-uppercase gs-0"
                                                                role="row">
                                                                <th class="min-w-100px dt-orderable-asc dt-orderable-desc"
                                                                    data-dt-column="0" rowspan="1" colspan="1"
                                                                    aria-label="order No.: Activate to sort" tabindex="0">
                                                                    <span class="dt-column-title" role="button">order
                                                                        No.</span><span class="dt-column-order"></span></th>
                                                                <th data-dt-column="1" rowspan="1" colspan="1"
                                                                    class="dt-orderable-asc dt-orderable-desc"
                                                                    aria-label="Status: Activate to sort" tabindex="0">
                                                                    <span class="dt-column-title"
                                                                        role="button">Status</span><span
                                                                        class="dt-column-order"></span>
                                                                </th>
                                                                <th data-dt-column="2" rowspan="1" colspan="1"
                                                                    class="dt-type-numeric dt-orderable-asc dt-orderable-desc"
                                                                    aria-label="Amount: Activate to sort" tabindex="0">
                                                                    <span class="dt-column-title"
                                                                        role="button">Amount</span><span
                                                                        class="dt-column-order"></span>
                                                                </th>
                                                                <th class="min-w-100px dt-orderable-asc dt-orderable-desc"
                                                                    data-dt-column="3" rowspan="1" colspan="1"
                                                                    aria-label="Rewards: Activate to sort" tabindex="0">
                                                                    <span class="dt-column-title"
                                                                        role="button">Rewards</span><span
                                                                        class="dt-column-order"></span>
                                                                </th>
                                                                <th class="min-w-100px dt-orderable-none"
                                                                    data-dt-column="4" rowspan="1" colspan="1"
                                                                    aria-label="Date"><span
                                                                        class="dt-column-title">Date</span><span
                                                                        class="dt-column-order"></span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                                            <tr>
                                                                <td>
                                                                    <a href="/keen/demo1/apps/ecommerce/sales/details.html"
                                                                        class="text-gray-600 text-hover-primary mb-1">#14469</a>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-light-success">Successful</span>
                                                                </td>
                                                                <td class="dt-type-numeric">
                                                                    $1,200.00 </td>
                                                                <td data-order="2025-01-12T00:00:00+01:00">
                                                                    120 </td>
                                                                <td>
                                                                    14 Dec 2020, 8:43 pm </td>
                                                            </tr>




                                                        </tbody>
                                                        <tfoot></tfoot>
                                                    </table>
                                                </div>
                                                <div id="" class="row">
                                                    <div id=""
                                                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                                    </div>
                                                    <div id=""
                                                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                        <div class="dt-paging paging_simple_numbers">
                                                            <nav>
                                                                <ul class="pagination">
                                                                    <li class="dt-paging-button page-item disabled"><button
                                                                            class="page-link previous" role="link"
                                                                            type="button"
                                                                            aria-controls="kt_table_customers_payment"
                                                                            aria-disabled="true" aria-label="Previous"
                                                                            data-dt-idx="previous" tabindex="-1"><i
                                                                                class="previous"></i></button></li>
                                                                    <li class="dt-paging-button page-item active"><button
                                                                            class="page-link" role="link"
                                                                            type="button"
                                                                            aria-controls="kt_table_customers_payment"
                                                                            aria-current="page" data-dt-idx="0">1</button>
                                                                    </li>
                                                                    <li class="dt-paging-button page-item"><button
                                                                            class="page-link" role="link"
                                                                            type="button"
                                                                            aria-controls="kt_table_customers_payment"
                                                                            data-dt-idx="1">2</button></li>
                                                                    <li class="dt-paging-button page-item"><button
                                                                            class="page-link next" role="link"
                                                                            type="button"
                                                                            aria-controls="kt_table_customers_payment"
                                                                            aria-label="Next" data-dt-idx="next"><i
                                                                                class="next"></i></button></li>
                                                                </ul>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Card-->
                                </div>
                                <!--end:::Tab pane-->



                            </div>
                            <!--end:::Tab content-->

                        </div>
                        <!--end::Content-->
                    </div>

                </div>
            </div>
        </div>

    </div>

  
@endsection
