@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Modification d'une Proforma libre 
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Modification</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid" x-data="invoiceEditForm({{ json_encode($facture) }}, {{ json_encode($facturelignes) }})" x-init="init()">
                <div class="app-container container-xxl">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                            <div class="card">
                                <div class="card-body p-12">
                                    <div>
                                        <!-- Date Échéance -->
                                        <div class="d-flex flex-column align-items-start flex-xxl-row">
                                            <div class="d-flex align-items-center flex-equal fw-row me-4 order-2">
                                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Date écheance:</div>
                                                <div class="position-relative d-flex align-items-center w-150px">
                                                    <input x-model="echeanceDate"
                                                        class="form-control form-control-transparent fw-bold pe-5"
                                                        type="date">
                                                </div>
                                            </div>

                                            <!-- Numéro Facture -->
                                            <div
                                                class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4">
                                                <span class="fs-2x fw-bold text-gray-800">Numéro de facture #</span>
                                                <input type="text" x-model="codefacture"
                                                    class="form-control form-control-flush fw-bold text-muted fs-3 w-125px"
                                                    readonly>
                                            </div>

                                            <!-- Date Création -->
                                            <div
                                                class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row">
                                                <div class="fs-6 fw-bold text-gray-700 text-nowrap">Date:</div>
                                                <div class="position-relative d-flex align-items-center w-150px">
                                                    <input x-model="created_at"
                                                        class="form-control form-control-transparent fw-bold pe-5"
                                                        type="date" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-10"></div>

                                        <!-- Informations sur le Client -->
                                        <div class="row gx-10 mb-5">
                                            <div class="col-lg-6">
                                                <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Facture adressée
                                                    à</label>
                                                <div class="mb-5">
                                                    <input type="text" x-model="nom"
                                                        class="form-control form-control-solid" placeholder="Nom">
                                                </div>
                                                <div class="mb-5">
                                                    <input type="text" x-model="email"
                                                        class="form-control form-control-solid" placeholder="Email">
                                                </div>
                                                <div class="mb-5">
                                                    <textarea x-model="adressepostale" class="form-control form-control-solid" rows="3" placeholder="Adresse postale"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label fs-6 fw-bold text-gray-700 mb-3"></label>
                                                <div class="mb-5">
                                                    <input type="text" x-model="prenom"
                                                        class="form-control form-control-solid" placeholder="Prénom">
                                                </div>
                                                <div class="mb-5">
                                                    <input type="text" x-model="telephone"
                                                        class="form-control form-control-solid" placeholder="Téléphone">
                                                </div>
                                                <div class="mb-5">
                                                    <textarea x-model="adressegeographique" class="form-control form-control-solid" rows="3"
                                                        placeholder="Adresse géographique"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ligne de Factures -->
                                        <div class="table-responsive mb-10">
                                            <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700">
                                                <thead>
                                                    <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                                        <th>Item</th>
                                                        <th>QTY</th>
                                                        <th>Prix</th>
                                                        <th>Total HT</th>
                                                        <th>TVA (18%)</th>
                                                        <th>Total TTC</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(item, index) in items" :key="index">
                                                        <tr>
                                                            <td><input x-model="item.name" type="text"
                                                                    class="form-control form-control-solid mb-2"
                                                                    placeholder="Item name"></td>
                                                            <td>
                                                                <input x-model.number="item.quantity" type="number"
                                                                    class="form-control form-control-solid text-end"
                                                                    @input="calculateAmounts(item); updateAllMontants()" />
                                                            </td>
                                                            <td>
                                                                <input x-model.number="item.price" type="number"
                                                                    class="form-control form-control-solid text-end"
                                                                    @input="calculateAmounts(item); updateAllMontants()" />
                                                            </td>
                                                            <td><input x-model.number="item.montantht" type="number"
                                                                    readonly
                                                                    class="form-control form-control-solid text-end"></td>
                                                            <td><input x-model.number="item.montanttva" type="number"
                                                                    readonly
                                                                    class="form-control form-control-solid text-end"></td>
                                                            <td><input x-model.number="item.montanttc" type="number"
                                                                    readonly
                                                                    class="form-control form-control-solid text-end"></td>
                                                            <td><button @click="removeItem(index)"
                                                                    class="btn btn-icon btn-active-color-primary">🗑️</button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <button @click="addItem()" class="btn btn-link">Ajouter</button>
                                        </div>

                                        <!-- Bouton pour basculer l'inclusion de la TVA -->
                                        <div class="d-flex justify-content-end mb-5">
                                            <button @click="toggleTVA" class="btn btn-warning">
                                                <span x-text="isTVAIncluded ? 'Exclure la TVA' : 'Inclure la TVA'"></span>
                                            </button>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <strong>Total TVA:</strong>
                                            <input x-model="totalTVA" type="text"
                                                class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <strong>Total HT:</strong>
                                            <input x-model="totalHT" type="text"
                                                class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <strong>Total TTC:</strong>
                                            <input x-model="totalTTC" type="text"
                                                class="form-control form-control-sm w-25 ms-2 text-end" readonly>
                                        </div>


                                        <!-- Totaux -->
                                        <div class="d-flex justify-content-end mt-3">
                                            <button @click="submitInvoice" class="btn btn-success btn-sm">Mettre à jour la
                                                facture</button> &nbsp; &nbsp;

                                            <a href="{{ route('factures.index') }}" class="btn btn-light btn-sm">Retourner
                                                facture</a>

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
        function invoiceEditForm(facture, facturelignes) {
            return {
                items: facturelignes.map(line => ({
                    name: line.designation,
                    quantity: line.quantite,
                    price: line.prix_unitaire,
                    montantht: line.montant_ht,
                    montanttva: line.montant_tva,
                    montanttc: line.montant_ttc,
                })),
                codefacture: facture.codefacture,
                echeanceDate: facture.date_echance ? facture.date_echance.split('T')[0] : '',
                created_at: facture.created_at ? facture.created_at.split('T')[0] : '', // Formater la date
                nom: facture.nom,
                prenom: facture.prenom,
                email: facture.email,
                telephone: facture.telephone,
                adressepostale: facture.adresse,
                adressegeographique: '',
                isTVAIncluded: facture.tvafacture,
                totalHT: 0, // Total HT
                totalTVA: 0, // Total TVA
                totalTTC: 0, // Total TTC
                init() {
                    this.updateAllMontants(); // Recalculer les totaux lorsque le composant est initialisé
                },
                addItem() {
                    this.items.push({
                        name: '',
                        quantity: 1,
                        price: 0,
                        montantht: 0,
                        montanttva: 0,
                        montanttc: 0
                    });
                    this.updateAllMontants(); // Mettre à jour les totaux lorsque l'élément est ajouté
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.updateAllMontants(); // Mettre à jour les totaux lorsque l'élément est supprimé
                },

                // Calcul du montant HT, TVA et TTC pour chaque ligne
                calculateAmounts(item) {
                    item.montantht = item.quantity * item.price;
                    if (this.isTVAIncluded) {
                        item.montanttva = item.montantht * 0.18; // TVA 18%
                    } else {
                        item.montanttva = 0;
                    }
                    item.montanttc = item.montantht + item.montanttva;
                },

                // Basculer l'état de l'inclusion de la TVA
                toggleTVA() {
                    this.isTVAIncluded = !this.isTVAIncluded;
                    this.updateAllMontants();
                },

                // Mettre à jour tous les montants en fonction de l'état de la TVA
                updateAllMontants() {
                    this.totalHT = 0;
                    this.totalTVA = 0;
                    this.totalTTC = 0;

                    this.items.forEach(item => {
                        this.calculateAmounts(item);
                        this.totalHT += item.montantht;
                        this.totalTVA += item.montanttva;
                        this.totalTTC += item.montanttc;
                    });
                },

                submitInvoice() {
                    const data = {
                        codefacture: this.codefacture,
                        created_at: this.created_at,
                        echeanceDate: this.echeanceDate,
                        nom: this.nom,
                        prenom: this.prenom,
                        email: this.email,
                        telephone: this.telephone,
                        adressepostale: this.adressepostale,
                        adressegeographique: this.adressegeographique,
                        items: this.items.map(item => ({
                            name: item.name,
                            quantity: item.quantity,
                            price: item.price,
                            montantht: item.montantht,
                            montanttva: item.montanttva,
                            montanttc: item.montanttc
                        })),

                        tvainclus: this.isTVAIncluded,
                        totalHT: this.totalHT,
                        totalTVA: this.totalTVA,
                        totalTTC: this.totalTTC
                    };

                    // Envoi des données au serveur
                    fetch('{{ route('factures.update', $facture->codefacture) }}', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(result => {
                            // Gérer la réponse du serveur (par exemple, afficher un message de succès)
                            console.log('Facture mise à jour avec succès:', result);
                            window.location.href = "{{ route('factures.index') }}";
                        })
                        .catch(error => {
                            // Gérer les erreurs (par exemple, afficher un message d'erreur)
                            console.error('Erreur lors de la mise à jour de la facture:', error);
                        });
                }

            };
        }
    </script>
@endpush
