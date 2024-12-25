@extends('layouts.app')
@section('content')
    <div class="app-main flex-column flex-row-fluid">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Création d'une facture personnalisée
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Formulaire</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid" x-data="invoiceForm()">
                <div class="app-container container-xxl">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                            <div class="card">
                                <div class="card-body p-12">
                                    <div>
                                        <div class="d-flex flex-column align-items-start flex-xxl-row">
                                            <div class="d-flex align-items-center flex-equal fw-row me-4 order-2">
                                                <!--begin::Date-->
                                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Date écheance:</div>
                                                <div class="position-relative d-flex align-items-center w-150px">
                                                    <input x-model="echeanceDate"
                                                        class="form-control form-control-transparent fw-bold pe-5 "
                                                        type="date">
                                                    <i class="ki-duotone ki-down fs-4 position-absolute ms-4 end-0"></i>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4"
                                                data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                data-bs-original-title="Enter Numero de facture number"
                                                data-kt-initialized="1">
                                                <span class="fs-2x fw-bold text-gray-800">Numero de facture #</span>
                                                <input type="text"
                                                    class="form-control form-control-flush fw-bold text-muted fs-3 w-125px"
                                                    value="" placeholder="..." readonly>
                                            </div>

                                            <div
                                                class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row">
                                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Date:</div>
                                                <div class="position-relative d-flex align-items-center w-150px">
                                                    <input x-model="created_at"
                                                        class="form-control form-control-transparent fw-bold pe-5 flatpickr-input"
                                                        placeholder="Select date" name="invoice_due_date" type="date"
                                                        value="">
                                                    <i class="ki-duotone ki-down fs-4 position-absolute end-0 ms-4"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-10"></div>
                                        <div class="col-md-4">
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
                                        </div>

                                        <div class="separator separator-dashed my-10"></div>
                                        <div class="mb-0">
                                            <div>
                                                <div class="table-responsive mb-10">
                                                    <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700">
                                                        <thead>
                                                            <tr
                                                                class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                                                <th class="min-w-300px w-475px">Désignation</th>
                                                                <th class="min-w-100px w-100px">Qte</th>
                                                                <th class="min-w-150px w-150px">Prix</th>
                                                                <th class="min-w-100px w-150px text-end">Total HT</th>
                                                                <th class="min-w-100px w-150px text-end">TVA (18%)</th>
                                                                <th class="min-w-100px w-150px text-end">Total TTC</th>
                                                                <th class="min-w-75px w-75px text-end">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <template x-for="(item, index) in items" :key="index">
                                                                <tr>
                                                                    <td>
                                                                        <input x-model="item.name" type="text"
                                                                            class="form-control form-control-solid mb-2"
                                                                            placeholder="Désignation"
                                                                            @input="calculateMontants(item)">
                                                                    </td>
                                                                    <td>
                                                                        <input x-model.number="item.quantity"
                                                                            type="number"
                                                                            class="form-control form-control-solid text-end"
                                                                            min="1" placeholder="1"
                                                                            @input="calculateMontants(item)">
                                                                    </td>
                                                                    <td>
                                                                        <input x-model.number="item.price" type="number"
                                                                            class="form-control form-control-solid text-end"
                                                                            placeholder="0.00"
                                                                            @input="calculateMontants(item)">
                                                                    </td>
                                                                    <td class="pt-8 text-end">
                                                                        <span x-text="item.montantht.toFixed(2)"></span>
                                                                    </td>
                                                                    <td class="pt-8 text-end">
                                                                        <span x-text="item.montanttva.toFixed(2)"></span>
                                                                    </td>
                                                                    <td class="pt-8 text-end">
                                                                        <span x-text="item.montanttc.toFixed(2)"></span>
                                                                    </td>
                                                                    <td><button @click="removeItem(index)"
                                                                            class="btn btn-icon btn-active-color-primary">🗑️</button>
                                                                    </td>

                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                    <button @click="addItem()" class="btn btn-link py-1">Ajouter</button>
                                                </div>
                                                <div class="d-flex justify-content-end mb-4">
                                                    <button @click="toggleTVA()" class="btn btn-primary btn-sm">
                                                        <span
                                                            x-text="isTVAIncluded ? 'Exclure TVA' : 'Inclure TVA'"></span>
                                                    </button>
                                                </div>

                                                <!-- Totaux avec inputs -->
                                                <div class="d-flex justify-content-end">
                                                    <strong>Total TVA:</strong>
                                                    <input x-model="totalTVA()" type="text"
                                                        class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                                </div>
                                                <div class="d-flex justify-content-end mt-2">
                                                    <strong>Total HT:</strong>
                                                    <input x-model="totalAmount()" type="text"
                                                        class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                                </div>
                                                <div class="d-flex justify-content-end mt-2">
                                                    <strong>Total TTC:</strong>
                                                    <input x-model="totalTTC()" type="text"
                                                        class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                                </div>

                                                <div class="d-flex justify-content-end mt-4">
                                                    <button @click="submitInvoice" class="btn btn-success">Enregistrer la
                                                        Facture</button> &nbsp;&nbsp;

                                                    <a href="{{ route('facturepersonnalite.index') }}"
                                                        class="btn btn-light btn-sm">Retourner
                                                        </a>
                                                </div>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fs-6 fw-bold text-gray-700">Notes</label>
                                                <textarea name="notes" class="form-control form-control-solid" rows="3"
                                                    placeholder="Thanks for your business"></textarea>
                                            </div>
                                        </div>
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
        function invoiceForm() {
            return {
                searchQuery: '',
                clients: @json($listeclients),
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
                    return this.clients.filter(client => client.libtiers.toLowerCase().includes(this.searchQuery
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

                // Méthodes pour gérer les éléments de facture
                items: [],
                isTVAIncluded: true,
                echeanceDate: '',
                created_at: '',

                // Ajouter un élément à la facture
                addItem() {
                    this.items.push({
                        name: '',
                        quantity: 1,
                        price: 0,
                        montantht: 0,
                        montanttva: 0,
                        montanttc: 0
                    });
                },

                // Calculer le montant total HT
                totalAmount() {
                    return this.items.reduce((total, item) => total + (item.quantity * item.price), 0);
                },

                // Calculer les montants HT, TVA et TTC
                calculateMontants(item) {
                    item.montantht = item.quantity * item.price;
                    item.montanttva = this.isTVAIncluded ? item.montantht * 0.18 : 0;
                    item.montanttc = item.montantht + item.montanttva;
                },

                // Mettre à jour tous les montants des éléments
                updateAllMontants() {
                    this.items.forEach(item => this.calculateMontants(item));
                },

                // Calcul des totaux HT, TVA, TTC
                totalHT() {
                    return this.items.reduce((total, item) => total + item.montantht, 0);
                },
                totalTVA() {
                    return this.items.reduce((total, item) => total + item.montanttva, 0);
                },
                totalTTC() {
                    return this.items.reduce((total, item) => total + item.montanttc, 0);
                },

                // Fonction pour basculer la TVA
                toggleTVA() {
                    this.isTVAIncluded = !this.isTVAIncluded;
                    this.updateAllMontants();
                },

                // Fonction pour supprimer un élément
                removeItem(index) {
                    this.items.splice(index, 1);
                },

                async submitInvoice() {
                    this.updateAllMontants();
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
