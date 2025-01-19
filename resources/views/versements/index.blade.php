@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="versementManager()">
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
                        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body pt-15">
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
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <template x-if="selectedClient.nom">
                                                    <div class="d-flex align-items-center">
                                                        <img x-bind:src="selectedClient.avatar ||
                                                            'https://via.placeholder.com/40'"
                                                            alt="Avatar" class="rounded-circle me-2" width="40"
                                                            height="40" />
                                                        <div>
                                                            <strong x-text="selectedClient.nom"></strong>
                                                            <br />
                                                            <span class="text-muted" x-text="selectedClient.email"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                <span x-text="selectedClient.nom ? '' : 'Aucun client sélectionné'"></span>
                                            </button>

                                            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                                <li class="px-2 py-1">
                                                    <input type="text" class="form-control" placeholder="Rechercher..."
                                                        x-model="searchQuery" />
                                                </li>

                                                <template x-for="client in filteredClients()" :key="client.id">
                                                    <li class="dropdown-item d-flex align-items-center"
                                                        @click="updateClientInfo(client.id, client.nom, client.prenom, client.matricule, client.telephone_parent, 'https://via.placeholder.com/40')"
                                                        style="cursor: pointer;">
                                                        <img src="{{ asset('avatar.png') }}" alt="Avatar"
                                                            class="rounded-circle me-2" width="40" height="40" />
                                                        <div>
                                                            <strong x-text="client.nom"></strong>
                                                            <br />
                                                            <span class="text-muted" x-text="client.matricule"></span>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="flex-lg-row-fluid ms-lg-15">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>Historique des versements </h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <div class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div id="" class="table-responsive">
                                            <table class="table align-middle table-row-dashed gy-5 dataTable"
                                                style="width: 100%;">
                                                <colgroup>
                                                    <col style="width: 118.344px;">
                                                    <col style="width: 100.469px;">
                                                    <col style="width: 88.1406px;">
                                                    <col style="width: 118.344px;">
                                                    <col style="width: 160.703px;">
                                                </colgroup>
                                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                                    <tr class="text-start text-muted text-uppercase gs-0">
                                                        <th class="min-w-100px">order
                                                            No.
                                                        </th>

                                                        <th class="min-w-100px">order
                                                            No.
                                                        </th>

                                                        <th class="min-w-100px">order
                                                            No.
                                                        </th>

                                                        <th class="min-w-100px">order
                                                            No.
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                    <tr>
                                                        <td>
                                                            <a href="/keen/demo1/apps/ecommerce/sales/details.html"
                                                                class="text-gray-600 text-hover-primary mb-1">#14469</a>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-success">Successful</span>
                                                        </td>
                                                        <td class="dt-type-numeric">
                                                            $1,200.00 </td>
                                                        <td data-order="2025-01-12T00:00:00+01:00">
                                                            120 </td>
                                                        <td>
                                                            14 Dec 2020, 8:43 pm </td>
                                                    </tr>

                                                </tbody>

                                            </table>
                                        </div>
                                        {{-- <div id="" class="row">
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
                                                                    class="page-link" role="link" type="button"
                                                                    aria-controls="kt_table_customers_payment"
                                                                    aria-current="page" data-dt-idx="0">1</button>
                                                            </li>
                                                            <li class="dt-paging-button page-item"><button
                                                                    class="page-link" role="link" type="button"
                                                                    aria-controls="kt_table_customers_payment"
                                                                    data-dt-idx="1">2</button></li>
                                                            <li class="dt-paging-button page-item"><button
                                                                    class="page-link next" role="link" type="button"
                                                                    aria-controls="kt_table_customers_payment"
                                                                    aria-label="Next" data-dt-idx="next"><i
                                                                        class="next"></i></button></li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>

                                </div>
                                
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        function versementManager() {
            return {
                searchQuery: '',
                clients: @json($listeleves),
                selectedClient: {
                    id: '',
                    nom: '',
                    email: '',
                    adressepostale: '',
                    adressegeographique: '',
                    telephone: '',
                    fax: '',
                    avatar: 'https://via.placeholder.com/40'
                },

                // Méthode de filtrage des clients selon la recherche
                filteredClients() {
                    return this.clients.filter(client => client.nom.toLowerCase().includes(this.searchQuery
                        .toLowerCase()));
                },
                // Méthode pour mettre à jour les informations du client sélectionné
                updateClientInfo(clientId, nom, email, adressepostale, telephone, avatar) {

                    this.selectedClient = {
                        id: clientId,
                        nom: nom || '',
                        email: email || '',
                        adressepostale: adressepostale || '',
                        telephone: telephone || '',
                        avatar: avatar || 'https://via.placeholder.com/40',
                    };
                    this.clientid = clientId;
                },

                async submitInvoice() {
                    // Vérifier si un client est sélectionné
                    if (!this.selectedClient.id) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Veuillez sélectionner un client.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    // Vérification qu'il y a au moins une ligne renseignée dans items
                    const isItemsValid = this.items.some(item => item.name && item.quantity > 0 && item.price > 0);

                    if (!isItemsValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Veuillez renseigner au moins une ligne.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    // Construction des données à envoyer
                    const data = {
                        items: this.items,
                        total_ht: this.totalHT(),
                        total_tva: this.totalTVA(),
                        total_ttc: this.totalTTC(),
                        echeance: this.echeanceDate,
                        clientid: this.selectedClient.id,
                        tvainclus: this.isTVAIncluded,
                    };
                    try {
                        // Envoi de la requête avec les données
                        const response = await fetch("{{ route('facturepersonnalite.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();

                        if (response.ok) {
                            alert('Facture enregistrée avec succès !');
                            window.location.href = "{{ route('facturepersonnalite.index') }}";
                        } else {
                            console.error(result);
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Une erreur est survenue.');
                    }
                },

            };
        }
    </script>
@endpush
