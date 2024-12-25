@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Modification d'une vente
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

            <div class="app-content flex-column-fluid" x-data="invoiceEditForm({{ json_encode($ventes) }}, {{ json_encode($venteslignes) }})" x-init="init()">
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
                                                <span class="fs-2x fw-bold text-gray-800">Numéro de vente #</span>
                                                <input type="text" x-model="numvente"
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
                                                        <th class="min-w-150px w-150px">Qté Disponible</th>
                                                        <th>Total HT</th>
                                                        <th>TVA (18%)</th>
                                                        <th>Total TTC</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(item, index) in items" :key="index">
                                                        <tr>
                                                            <td>
                                                                <select x-model="item.tproduct_id"
                                                                    class="form-control form-control-solid mb-2"
                                                                    @change="updatePrice($event, item)">
                                                                    <option value="">Choisir un produit</option>
                                                                    @foreach ($listeproduct as $produit)
                                                                        <option value="{{ $produit->id }}"
                                                                            data-price="{{ $produit->prixvente }}"
                                                                            data-qte="{{ $produit->qtedisponible }}">
                                                                            {{ $produit->libelleproduct }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

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

                                                            <td>
                                                                <!-- Input pour afficher et modifier la quantité disponible -->
                                                                <input x-model.number="item.qtedisponible" type="number"
                                                                    class="form-control form-control-solid text-end"
                                                                    readonly>
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
                                                vente</button> &nbsp; &nbsp;

                                            <a href="{{ route('ventes.index') }}" class="btn btn-light btn-sm">Retourner
                                            </a>

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
        function invoiceEditForm(ventes, venteslignes) {
            return {
                items: venteslignes.map(line => ({
                    name: line.designation,
                    tproduct_id: line.tproduct_id,
                    quantity: line.quantite,
                    price: line.prix_unitaire,
                    montantht: line.montant_ht,
                    qtedisponible: line.product.qtedisponible,
                    montanttva: line.montant_tva,
                    montanttc: line.montant_ttc,
                })),
                numvente: ventes.numvente,
                echeanceDate: ventes.date_echance ? ventes.date_echance.split('T')[0] : '',
                created_at: ventes.created_at ? ventes.created_at.split('T')[0] : '', // Formater la date
                nom: ventes.nom,
                prenom: ventes.prenom,
                email: ventes.email,
                telephone: ventes.telephone,
                adressepostale: ventes.adresse,
                adressegeographique: ventes.tvafacture,
                isTVAIncluded: ventes.tvafacture,
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


                    if (item.quantity > item.qtedisponible) {
                        // Alerter l'utilisateur que l'achat est impossible
                        alert(
                            "Quantité demandée supérieure à la quantité disponible. Impossible de traiter cette commande."
                        );

                        // Mettre la quantité à 0 et ajouter le statut "Impossible"
                        item.quantity = 0;
                        item.montantht = 0;
                        item.montanttva = 0;
                        item.montanttc = 0;

                        return;
                    } else {
                        // alert('continue')
                    }



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

                updatePrice(event, item) {


                    // Récupérer l'élément select correspondant à ce produit
                    const selectedOption = event.target.selectedOptions[0];

                    if (!selectedOption) {
                        alert("Aucune option sélectionnée.");
                        return;
                    }
                    // Extraire le prix de vente de l'attribut data-price
                    const priceAttribute = selectedOption.getAttribute('data-price');



                    // Extraire la quantité disponible de l'attribut data-qte
                    const qteDisponible = parseInt(selectedOption.getAttribute('data-qte'));

                    // Mettre à jour le prix et la quantité disponible du produit dans le modèle
                    item.price = priceAttribute;
                    item.qtedisponible = qteDisponible;

                    // Calculer à nouveau les montants HT, TVA, et TTC
                    this.calculateMontants(item);
                },


                calculateMontants(item) {

                    // Vérification si la quantité demandée est supérieure à la quantité disponible
                    if (item.quantity > item.qtedisponible) {
                        // Alerter l'utilisateur que l'achat est impossible
                        alert(
                            "Quantité demandée supérieure à la quantité disponible. Impossible de traiter cette commande."
                        );

                        // Mettre la quantité à 0 et ajouter le statut "Impossible"
                        item.quantity = 0;
                        item.montantht = 0;
                        item.montanttva = 0;
                        item.montanttc = 0;

                        return;
                    } else {

                    }

                    // Montant HT
                    item.montantht = item.quantity * item.price;

                    // Montant TVA (18% si la TVA est incluse)
                    item.montanttva = this.isTVAIncluded ? item.montantht * 0.18 : 0;

                    // Montant TTC
                    item.montanttc = item.montantht + item.montanttva;

                    this.totalHT += item.montantht;
                    this.totalTVA += item.montanttva;
                    this.totalTTC += item.montanttc;
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
                        items: this.items,
                        isTVAIncluded: this.isTVAIncluded,
                        totalHT: this.totalHT,
                        totalTVA: this.totalTVA,
                        totalTTC: this.totalTTC
                    };

                    // Envoi des données au serveur
                    fetch('{{ route('ventes.update', $ventes->numvente) }}', {
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
                            window.location.href = "{{ route('ventes.index') }}";
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
