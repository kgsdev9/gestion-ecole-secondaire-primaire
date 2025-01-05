@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4" x-data="productPost()">
        <div class="row">
            <!-- Products Section -->
            <div class="col-md-8 p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="position-relative w-100">
                        <!-- Chevron gauche -->
                        <button class="btn btn-light position-absolute start-0 top-50 translate-middle-y" @click="scrollLeft"
                            style="z-index: 1;" x-show="isScrollable">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <!-- Liste des catégories scrollable -->
                        <div class="d-flex overflow-auto px-4"
                            style="scroll-behavior: smooth; white-space: nowrap; max-width: 100%;" ref="categoryContainer"
                            @scroll="checkScrollState">
                            <template x-for="category in categories" :key="category.id">
                                <button class="btn btn-outline-secondary me-2"
                                    :class="{ 'active': selectedCategory === category.id }"
                                    @click="filterByCategory(category.id)" x-text="category.libellecategorieproduct">
                                </button>

                            </template>
                        </div>

                        <!-- Chevron droit -->
                        <button class="btn btn-light position-absolute end-0 top-50 translate-middle-y" @click="scrollRight"
                            style="z-index: 1;" x-show="isScrollable">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Barre de recherche -->
                    <input type="text" class="form-control w-25" placeholder="Rechercher..." x-model="search">
                </div>

                <!-- Produits -->
                <div class="row">
                    <template x-for="product in filteredProducts()" :key="product.id">
                        <div class="col-md-3 mb-3">
                            <div class="product-card">
                                <img :src="product.image_url" alt="Product" class="img-fluid">
                                <h6 class="mt-2" x-text="product.libelleproduct"></h6>
                                <p class="text-muted" x-text="`${product.prixvente} FCFA`"></p> <!-- Prix en FCFA -->
                                <button class="btn btn-sm btn-primary" @click="addToCart(product)">Ajouter au
                                    panier</button>
                            </div>
                        </div>
                    </template>

                </div>
            </div>


            <!-- Order Section -->
            <div class="col-md-4 order-section">
                <h5 class="mb-3">Sommaire de la commande</h5>
                <button class="btn btn-sm btn-outline-secondary mb-3" @click="shownNameClient()">+ Ajouter un
                    client</button>
                <div class="" x-show="shownameclient">
                    <input type="text" x-model="nom" class="form-control-sm" placeholder="Nom du clien ">
                </div>

                <div>
                    <label for="restaurant-select">Sélectionner une Table </label>
                    <select id="restaurant-select" class="form-select mt-2" x-model="table">
                        <option value="">Choisir une table </option>
                        <template x-for="restaurant in listetabrestaurant" :key="restaurant.id">
                            <option :value="restaurant.id" x-text="restaurant.name"></option>
                        </template>
                    </select>
                </div>


                <div class="mt-2">
                    <label for="restaurant-select">Sélectionner un serveur </label>
                    <select id="restaurant-select" x-model="serveur" class="form-select mt-2">
                        <option value="">Choisir un serveur</option>
                        <template x-for="seveur in listeserveurs" :key="seveur.id">
                            <option :value="seveur.id" x-text="seveur.name"></option>
                        </template>
                    </select>
                </div>

                <div class="mt-2">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="order-item d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">

                                <img :src="item.image_url" alt="Product Image" class="img-thumbnail"
                                    style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                <div>
                                    <strong x-text="item.libelleproduct"></strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <!-- Boutons pour augmenter/diminuer la quantité -->
                                <button class="btn btn-sm btn-outline-secondary" @click="decreaseQuantity(index)">-</button>
                                <span class="mx-2" x-text="item.quantity"></span>
                                <button class="btn btn-sm btn-outline-secondary" @click="increaseQuantity(index)">+</button>
                            </div>
                            <strong x-text="`${item.prixvente * item.quantity} FCFA`"></strong>

                            <button :class="item.offert ? 'btn btn-sm btn-success' : 'btn btn-sm btn-danger'"
                                @click="markAsOffered(index)">
                                <span x-text="item.offert ? 'Offerte' : 'Non Offert'"></span>
                            </button>


                        </div>
                    </template>

                </div>

                <div class="mb-4">
                    <h4>Modes de Règlement</h4>
                    <div class="d-flex flex-wrap">
                        <template x-for="mode in listemodereglement" :key="mode.id">
                            <div class="me-3 mb-2">
                                <input type="radio" :id="'mode-' + mode.id" :name="'modeReglement'"
                                    :value="mode.id" class="form-check-input" x-model="selectedModeReglement">
                                <label :for="'mode-' + mode.id" x-text="mode.libellemodereglement"
                                    class="form-check-label"></label>
                            </div>
                        </template>
                    </div>
                </div>


                <div class="mt-4">

                    <div class="d-flex justify-content-between">
                        <h5>NET A PAYER :</h5>
                        <h5 x-text="`${total} FCFA`"></h5>
                    </div>
                </div>

                <button class="btn btn-pay w-100 mt-3" @click="confirmOrder()">Confirmer la commande</button>
            </div>
        </div>
    </div>

    <script>
        function productPost() {
            return {
                search: '',
                products: @json($listeProducts),
                categories: @json($listecategories),
                listemodereglement: @json($listemodereglement),
                listetabrestaurant: @json($listetabrestaurant),
                listeserveurs: @json($listeserveurs),
                selectedCategory: '',
                isScrollable: false,
                shownameclient: false,
                nom: '',
                selectedModeReglement: null,
                table: null,
                serveur: null,
                cart: [],
                total: 0, // Initialisation de la propriété total

                filteredProducts() {
                    return this.products.filter(product => {
                        const matchesSearch = product.libelleproduct.toLowerCase().includes(this.search
                            .toLowerCase());
                        const matchesCategory = this.selectedCategory ? product.tcategorieproduct_id === this
                            .selectedCategory : true;
                        return matchesSearch && matchesCategory;
                    });
                },

                shownNameClient() {
                    this.shownameclient = !this.shownameclient; // Alterne entre vrai et faux
                },



                markAsOffered(index) {
                    const item = this.cart[index];

                    // Si l'article est déjà offert, on le remet à son prix initial
                    if (item.offert) {
                        // Vérifie que originalPrix est bien défini et qu'il est un nombre
                        if (item.originalPrix !== undefined && item.originalPrix !== null && !isNaN(item.originalPrix)) {
                            // Recalcule le prix en fonction de la quantité
                            item.prixvente = item.originalPrix * item.quantity;
                        } else {

                            alert(item.originalPrix);
                            console.warn(`Prix initial non défini ou invalide pour l'article ${item.libelleproduct}`);
                            item.prixvente = 0; // Définit le prix à zéro si le prix initial est invalide
                        }
                        item.offert = false; // Marque comme non offert
                    } else {
                        // Si l'article n'est pas offert, on le marque comme offert
                        item.prixvente = 0; // Met le prix à zéro pour l'article offert
                        item.offert = true; // Marque comme offert
                    }
                    // Mettre à jour le total après chaque modification de prix
                    this.updateTotal();
                },

                updateTotal() {
                    // Calcul du total en fonction des prix et des quantités des produits dans le panier
                    this.total = this.cart.reduce((acc, item) => {
                        return acc + (item.prixvente * item
                            .quantity); // Calcul du total en fonction de la quantité et du prix
                    }, 0);
                },

                filterByCategory(categoryId) {
                    this.selectedCategory = this.selectedCategory === categoryId ? '' : categoryId;
                },

                addToCart(product) {
                    const existingProduct = this.cart.find(item => item.id === product.id);
                    if (existingProduct) {
                        existingProduct.quantity++;
                    } else {
                        this.cart.push({
                            ...product,
                            quantity: 1,
                            originalPrix: product.prixvente, // Assurez-vous que le prix original est défini ici
                        });
                    }
                    this.updateTotal(); // Met à jour le total après l'ajout
                },


                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.updateTotal(); // Met à jour le total après la suppression
                },

                increaseQuantity(index) {
                    this.cart[index].quantity++;
                    this.updateTotal(); // Met à jour le total après l'augmentation
                },

                decreaseQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    } else {
                        this.removeFromCart(index);
                    }
                    this.updateTotal(); // Met à jour le total après la diminution
                },

                scrollLeft() {
                    const container = this.$refs.categoryContainer;
                    container.scrollLeft -= 100; // Ajustez la valeur selon vos besoins
                },

                scrollRight() {
                    const container = this.$refs.categoryContainer;
                    container.scrollLeft += 100; // Ajustez la valeur selon vos besoins
                },


                calculateTotal() {
                    const total = this.cart.reduce((sum, item) => sum + item.prixvente * item.quantity, 0);

                    return (total).toFixed(2); // Total avec taxe
                },

                async confirmOrder() {


                    if (!this.cart || this.cart.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le panier est vide.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.nom || this.nom.trim() === '') {
                        if (!this.table || this.table.trim() === '') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Veuillez sélectionner une table.',
                                showConfirmButton: true
                            });
                            this.isLoading = false;
                            return;
                        }
                    }

                    // if (!this.table || this.table.trim() === '') {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Veuillez sélectionner une table.',
                    //         showConfirmButton: true
                    //     });
                    //     this.isLoading = false;
                    //     return;
                    // }
                    if (!this.serveur || this.serveur.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Veuillez sélectionner un serveur.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }
                    if (this.totalttc <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le total TTC est incorrect.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }
                    if (!this.selectedModeReglement || this.selectedModeReglement === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Veuillez sélectionner un mode de règlement.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }
                    const data = {
                        items: this.cart,
                        table: this.table,
                        serveur: this.serveur,
                        totalttc: this.calculateTotal(),
                        nom: this.nom,
                        modereglement_id: this.selectedModeReglement,
                    };




                    try {
                        const response = await fetch("{{ route('ventes.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });

                        if (response.ok) {

                            window.location.href = "{{ route('ventes.index') }}";

                        } else {
                            alert('Erreur lors de l\'enregistrement');
                        }
                    } catch (error) {
                        console.error('Erreur lors de l\'enregistrement de la facture', error);
                    }
                },


            };
        }
    </script>
@endsection
