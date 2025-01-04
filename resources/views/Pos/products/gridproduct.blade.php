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
                                <p class="text-muted" x-text="`₹${product.prixvente}`"></p>
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
                <button class="btn btn-sm btn-outline-secondary mb-3">+ Ajouter un client</button>
                <div>
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="order-item d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong x-text="item.libelleproduct"></strong>
                                <p class="text-muted mb-0">Regular</p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary" @click="decreaseQuantity(index)">-</button>
                                <span class="mx-2" x-text="item.quantity"></span>
                                <button class="btn btn-sm btn-outline-secondary" @click="increaseQuantity(index)">+</button>
                            </div>
                            <strong x-text="`₹${item.prixvente * item.quantity}`"></strong>
                            <button class="btn btn-sm btn-danger" @click="removeFromCart(index)">✕</button>
                        </div>
                    </template>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <span>Taxes :</span>
                        <span x-text="`₹${calculateTax()}`"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h5>Total :</h5>
                        <h5 x-text="`₹${calculateTotal()}`"></h5>
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
                selectedCategory: '',
                isScrollable: false,
                cart: [],
                filteredProducts() {
                    return this.products.filter(product => {
                        const matchesSearch = product.libelleproduct.toLowerCase().includes(this.search
                            .toLowerCase());
                        const matchesCategory = this.selectedCategory ? product.tcategorieproduct_id === this
                            .selectedCategory : true;
                        return matchesSearch && matchesCategory;
                    });
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
                            quantity: 1
                        });
                    }
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                increaseQuantity(index) {
                    this.cart[index].quantity++;
                },
                decreaseQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                scrollLeft() {
                    const container = this.$refs.categoryContainer;
                    container.scrollLeft -= 100; // Ajustez la valeur selon vos besoins
                },


                scrollRight() {
                    const container = this.$refs.categoryContainer;
                    container.scrollLeft += 100; // Ajustez la valeur selon vos besoins
                },
                calculateTax() {
                    const total = this.cart.reduce((sum, item) => sum + item.prixvente * item.quantity, 0);
                    return (total * 0.1).toFixed(2); // 10% tax
                },
                calculateTotal() {
                    const total = this.cart.reduce((sum, item) => sum + item.prixvente * item.quantity, 0);
                    const tax = total * 0.1; // 10% tax
                    return (total + tax).toFixed(2);
                },
                confirmOrder() {
                    alert('Commande confirmée ! Merci pour votre achat.');
                    this.cart = [];
                },
            };
        }
    </script>
@endsection
