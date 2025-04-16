@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="productSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            SUIVI DES VERSEMENTS DES ELEVES
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Eleves</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class='fas fa-search  position-absolute ms-5'></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterProducts">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select x-model="selectedCategory" @change="filterByCategory"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les classes </option>
                                            <template x-for="category in categories" :key="category.id">
                                                <option :value="category.id" x-text="category.name  ">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <select x-model="selectedCategory" @change="filterByCategory"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les annees academique </option>
                                            <template x-for="category in categories" :key="category.id">
                                                <option :value="category.id" x-text="category.name  ">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <button @click="printProducts" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                    <button @click="exportProducts" class="btn btn-light-primary btn-sm">
                                        <i class='fas fa-file-export'></i> Export
                                    </button>



                                </div>
                            </div>
                        </div>

                        <div class="card-body py-4">
                            <div class="table-responsive">
                                <template x-if="isLoading">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!isLoading">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-125px">Code Product</th>
                                                <th class="min-w-125px">Prix d'achat </th>
                                                <th class="min-w-125px">Prix De Vente</th>
                                                <th class="min-w-125px">Qte</th>
                                                <th class="min-w-125px">Date de création </th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="product in paginatedProducts" :key="product.id">
                                                <tr>
                                                    <td class="d-flex align-items-center">
                                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                            <div class="symbol-label">
                                                                <!-- Ne pas dupliquer l'URL dans 'src', utilisez directement 'product.image_url' -->
                                                                <img :src="product.image_url" alt="Image"
                                                                    class="w-100 h-100">
                                                            </div>


                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="#" class="text-gray-800 text-hover-primary mb-1"
                                                                x-text="product.libelleproduct"></a>
                                                            <span x-text="product.category.libellecategorieproduct"></span>
                                                        </div>
                                                    </td>
                                                    <td x-text="product.prixachat"></td>
                                                    <td x-text="product.prixvente"></td>
                                                    <td x-text="product.qtedisponible"></td>
                                                    <td x-text="new Date(product.created_at).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td class="text-end">

                                                        <button @click="openModal(product)"
                                                            class="btn btn-primary btn-sm mx-2"> <i
                                                                class="fa fa-edit"></i></button>

                                                        <button @click="deleteProduct(product.id)"
                                                            class="btn btn-danger btn-sm ms-2 btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>



                                                    </td>

                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </template>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-7 offset-md-5 d-flex justify-content-end">
                                    <nav>
                                        <ul class="pagination">
                                            <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                                <button class="page-link"
                                                    @click="goToPage(currentPage - 1)">Précedent</button>
                                            </li>
                                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                                <button class="page-link"
                                                    @click="goToPage(currentPage + 1)">Suivant</button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function productSearch() {
            return {
                searchTerm: '',
                categories: @json($listeclasse),
                products: @json($listeeleves),
                filteredProducts: [],
                selectedCategory: '',
                showCategorySelect: true,
                currentPage: 1,
                productsPerPage: 10,
                totalPages: 0,
                isLoading: true,
                showModal: false,
                isEdite: false,
                formData: {
                    name: '',
                    prixachat: '',
                    qtedisponible: '',
                    prixvente: '',
                    image: '',
                    imagePreview: null,
                    category_id: ''
                },
                currentProduct: null,

                hideModal() {

                    this.showModal = false;
                    this.currentProduct = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                openModal(product = null) {
                    this.isEdite = product !== null;
                    if (this.isEdite) {
                        this.currentProduct = {
                            ...product
                        };
                        this.formData = {
                            name: this.currentProduct.libelleproduct,
                            prixachat: this.currentProduct.prixachat,
                            prixvente: this.currentProduct.prixvente,
                            qtedisponible: this.currentProduct.qtedisponible,
                            category_id: this.currentProduct.category.id,
                            image: null,
                            imagePreview: this.currentProduct.image_url || '/default-image.jpg',
                        };
                    } else {
                        this.resetForm();

                        this.isEdite = false;
                    }
                    this.showModal = true;
                },


                handleFileChange(event) {
                    // Récupère le fichier sélectionné
                    const file = event.target.files[0];

                    if (file) {
                        // Met à jour l'image dans formData
                        this.formData.image = file;

                        // Crée un aperçu de l'image en utilisant FileReader
                        const reader = new FileReader();
                        reader.onload = () => {
                            this.formData.imagePreview = reader.result; // Met à jour l'aperçu
                        };
                        reader.readAsDataURL(file); // Lire l'image en tant qu'URL base64
                    }
                },

                resetForm() {
                    this.formData = {
                        name: '',
                        prixachat: '',
                        prixvente: '',
                        category_id: '',
                        qtedisponible: '',
                        imagePreview: null,
                        image: null,
                    };
                    document.getElementById('image').value = '';
                },


                async submitForm() {
                    this.isLoading = true;

                    if (!this.formData.name || this.formData.name.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom du produit est requis.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }

                    if (!this.formData.prixachat || isNaN(this.formData.prixachat) || parseFloat(this.formData
                            .prixachat) <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le prix du produit doit être un nombre valide et supérieur à 0.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    if (!this.formData.prixvente || isNaN(this.formData.prixvente) || parseFloat(this.formData
                            .prixvente) <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le prix de vente du produit doit être un nombre valide et supérieur à 0.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }


                    if (!this.formData.qtedisponible || isNaN(this.formData.qtedisponible) || parseFloat(this.formData
                            .qtedisponible) <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'La quantité disponible doit être un nombre valide et supérieur à 0.',
                            showConfirmButton: true
                        });
                        this.isLoading = false;
                        return;
                    }
                    const formData = new FormData();
                    formData.append('name', this.formData.name);
                    formData.append('prixachat', this.formData.prixachat);
                    formData.append('prixvente', this.formData.prixvente);
                    formData.append('category_id', this.formData.category_id);
                    formData.append('qtedisponible', this.formData.qtedisponible);
                    formData.append('product_id', this.currentProduct ? this.currentProduct.id : null);
                    if (this.formData.image) {
                        formData.append('image', this.formData.image);
                    }

                    try {
                        const response = await fetch('{{ route('product.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const product = data.product;

                            if (product) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produit enregistré avec succès !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                if (this.isEdite) {
                                    const index = this.products.findIndex(p => p.id === product.id);
                                    if (index !== -1) {
                                        this.products[index] = product;
                                    }

                                } else {
                                    this.products.push(product);
                                    this.products.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                }

                                this.filterProducts();
                                this.resetForm();
                                this.hideModal();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Produit non valide.',
                                    showConfirmButton: true
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de l\'enregistrement.',
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
                    } finally {
                        this.isLoading = false;
                    }
                },


                get paginatedProducts() {
                    let start = (this.currentPage - 1) * this.productsPerPage;
                    let end = start + this.productsPerPage;
                    return this.filteredProducts.slice(start, end);
                },


                filterProducts() {

                    const term = this.searchTerm.toLowerCase();
                    this.filteredProducts = this.products.filter(product => {
                        return product.libelleproduct && product.libelleproduct.toLowerCase().includes(
                            term);
                    });
                    this.totalPages = Math.ceil(this.filteredProducts.length / this.productsPerPage);
                    this.currentPage = 1;
                },

                printProducts() {
                    let printContent = '<h1>Liste des Produits</h1>';
                    printContent +=
                        '<table border="1"><thead><tr><th>ID</th><th>Nom</th><th>Catégorie</th></tr></thead><tbody>';

                    this.filteredProducts.forEach(product => {
                        printContent +=
                            `<tr><td>${product.id}</td><td>${product.libelleproduct}</td><td>${product.category.libellecategorieproduct}</td></tr>`;
                    });

                    printContent += '</tbody></table>';

                    const printWindow = window.open('', '', 'height=500,width=800');
                    printWindow.document.write('<html><head><title>Impression des produits</title></head><body>');
                    printWindow.document.write(printContent);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                },

                exportProducts() {
                    let csvContent = "ID,Nom,Catégorie\n";

                    this.filteredProducts.forEach(product => {
                        csvContent +=
                            `${product.id},${product.libelleproduct},${product.category.libellecategorieproduct}\n`;
                    });

                    // Créer un fichier CSV et le télécharger
                    const blob = new Blob([csvContent], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const link = document.createElement("a");
                    const url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", "produits_filtrés.csv");
                    link.click();
                },


                filterByCategory() {
                    // Réinitialiser filteredProducts à la liste complète des produits
                    this.filteredProducts = this.products;

                    if (this.selectedCategory) {
                        // Appliquer le filtre sur les produits par catégorie
                        this.filteredProducts = this.filteredProducts.filter(product => product.category.id === parseInt(
                            this.selectedCategory));
                    }

                    // Optionnel : Appliquer également un filtrage par recherche textuelle (si nécessaire)
                    if (this.searchTerm) {
                        this.filteredProducts = this.filteredProducts.filter(product => {
                            return product.libelleproduct.toLowerCase().includes(this.searchTerm.toLowerCase());
                        });
                    }

                    // Calculer le nombre de pages en fonction du nombre de produits filtrés
                    this.totalPages = Math.ceil(this.filteredProducts.length / this.productsPerPage);
                },

                async deleteProduct(productId) {
                    try {
                        const url =
                            `{{ route('product.destroy', ['product' => '__ID__']) }}`.replace(
                                "__ID__",
                                productId
                            );

                        const response = await fetch(url, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: result.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                // Retirer le produit de la liste `this.products`
                                this.products = this.products.filter(product => product.id !== productId);

                                // Après suppression, appliquer le filtre pour mettre à jour la liste affichée
                                this.filterProducts();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: result.message,
                                    showConfirmButton: true,
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erreur lors de la requête.",
                                showConfirmButton: true,
                            });
                        }
                    } catch (error) {
                        console.error("Erreur réseau :", error);
                        Swal.fire({
                            icon: "error",
                            title: "Une erreur réseau s'est produite.",
                            showConfirmButton: true,
                        });
                    }
                },

                goToPage(page) {
                    if (page < 1 || page > this.totalPages) return;
                    this.currentPage = page;
                },

                init() {
                    this.filterProducts();
                    this.isLoading = false;
                }
            };
        }
    </script>
@endsection
