@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="productSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES MOYENNES EXAMENS
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Notes</li>
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
                                        placeholder="Rechercher" x-model="searchTerm">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select x-model="selectedCategory" @change="filterClasse"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les classes </option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <select x-model="selectedCategory" @change="filterLevel"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les Niveau</option>
                                            <template x-for="niveau in niveaux" :key="niveau.id">
                                                <option :value="niveau.id" x-text="niveau.name">
                                                </option>
                                            </template>
                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="card-body py-4">
                            <div class="container mt-5">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <input type="text"
                                            class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                            placeholder="Rechercher" x-model="searchTerm" @input="filterProducts">
                                    </div>
                                    <div class="col-md-3">
                                        <select x-model="selectedCategory" @change="filterByCategory"
                                            class="form-select form-select-sm" data-live-search="true">
                                            <option value="">Toutes les classes </option>
                                            <template x-for="classe in classes" :key="classe.id">
                                                <option :value="classe.id" x-text="classe.name">
                                                </option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text"
                                            class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                            placeholder="Entrer un note " x-model="searchTerm">
                                    </div>

                                    <div class="col-md-3">
                                        <button @click="showModal = true"
                                            class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm">
                                            <i class="fa fa-add"></i> Création
                                        </button>
                                    </div>

                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                @foreach ($matieres as $matiere)
                                                    <th>{{ $matiere->name }}</th>
                                                @endforeach
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eleves as $eleve)
                                                <tr>
                                                    <td>{{ $eleve->eleve->nom }}</td>
                                                    <td>{{ $eleve->eleve->prenom }}</td>
                                                    @foreach ($matieres as $matiere)
                                                        <td>
                                                            @php
                                                                $note = $eleve->eleve->notes
                                                                    ->where('matiere_id', $matiere->id)
                                                                    ->first();
                                                            @endphp
                                                            <input type="number" class="form-control"
                                                                value="{{ $note->note ?? '' }}">
                                                        </td>
                                                    @endforeach

                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                           
                                         
                                        </tbody>
                                    </table>
                                </div>
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



        <template x-if="showModal">
            <div class="modal fade show d-block" tabindex="-1" aria-modal="true" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Libellé du Produit</label>
                                    <input type="text" id="name" class="form-control" x-model="formData.name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="prixachat" class="form-label">Prix d'Achat</label>
                                    <input type="number" id="prixachat" class="form-control"
                                        x-model="formData.prixachat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="prixvente" class="form-label">Prix de Vente</label>
                                    <input type="number" id="prixvente" class="form-control"
                                        x-model="formData.prixvente" required>
                                </div>

                                <div class="mb-3">
                                    <label for="qtedisponible" class="form-label">Qte disponible</label>
                                    <input type="number" id="qtedisponible" class="form-control"
                                        x-model="formData.qtedisponible" required>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="name" class="form-label">Catégorie</label>
                                    <select x-model="formData.category_id" class="form-select">
                                        <option value="">Choisir un produit</option>
                                        @foreach ($listecategorie as $category)
                                            <option value="{{ $category->id }}">{{ $category->libellecategorieproduct }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" id="image" class="form-control" @change="handleFileChange"
                                        :required="!isEdite">
                                </div>


                                <div v-if="formData.imagePreview" class="mt-3">
                                    <img :src="formData.imagePreview" alt="Image Preview" class="img-fluid"
                                        style="max-height:100px;">
                                </div>

                                <button type="submit" class="btn btn-primary"
                                    x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>


    </div>

    <script>
        function productSearch() {
            return {
                searchTerm: '',
                niveaux: @json($niveaux),
                classes: @json($classes),
                products: [],
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
