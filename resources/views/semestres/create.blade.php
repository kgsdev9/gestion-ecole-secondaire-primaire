@extends('layouts.app')
@section('title', 'Gestion des Semestres')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="semestreManagement()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES SEMESTRES - Année académique : {{ $anneacademique->name }}
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Semestres</li>
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
                                    <i class='fas fa-search position-absolute ms-5'></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher un semestre" x-model="searchTerm" @input="filterSemestres">
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button @click="openModal()" class="btn btn-light btn-active-light-primary btn-sm">
                                        <i class="fa fa-add"></i> Ajouter un semestre
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
                                                <th class="min-w-125px">Libellé</th>
                                                <th class="min-w-125px">Date début</th>
                                                <th class="min-w-125px">Date fin</th>
                                                <th class="min-w-125px">Clôture</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="semestre in paginatedSemestres" :key="semestre.id">
                                                <tr>
                                                    <td x-text="semestre.name"></td>
                                                    <td x-text="formatDate(semestre.date_debut)"></td>
                                                    <td x-text="formatDate(semestre.date_fin)"></td>
                                                    <td>
                                                        <span
                                                            :class="semestre.cloture ? 'badge bg-success' : 'badge bg-warning'">
                                                            <i
                                                                :class="semestre.cloture ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                                                            <span x-text="semestre.cloture ? 'Clôturé' : 'Ouvert'"></span>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button @click="toggleCloture(semestre)"
                                                            class="btn btn-secondary ms-2 btn-sm">
                                                            <i class="fa"
                                                                :class="semestre.cloture ? 'fa-unlock' : 'fa-lock'"></i>
                                                            <span
                                                                x-text="semestre.cloture ? 'Déclôturer' : 'Clôturer'"></span>
                                                        </button>
                                                        <button @click="deleteSemestre(semestre.id)"
                                                            class="btn btn-danger btn-sm">
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
                                                    @click="goToPage(currentPage - 1)">Précédent</button>
                                            </li>
                                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                                <button class="page-link"
                                                    @click="goToPage(currentPage + 1)">Suivant</button>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <template x-if="showModal">
                <div class="modal fade show d-block" tabindex="-1" aria-modal="true"
                    style="background-color: rgba(0,0,0,0.5)">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajout de semestre</h5>
                                <button class="btn-close" @click="closeModal()"></button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addSemestre">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Libellé</label>
                                        <input type="text" id="name" class="form-control" x-model="form.name"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_debut" class="form-label">Date début</label>
                                        <input type="date" id="date_debut" class="form-control" x-model="form.date_debut"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_fin" class="form-label">Date fin</label>
                                        <input type="date" id="date_fin" class="form-control"
                                            x-model="form.date_fin" required>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <script>
            function semestreManagement() {
                return {
                    searchTerm: '',
                    semestres: @json($semestres),
                    filteredSemestres: [],
                    currentPage: 1,
                    semestresPerPage: 10,
                    totalPages: 0,
                    isLoading: false,
                    showModal: false,
                    form: {
                        name: '',
                        date_debut: '',
                        date_fin: '',
                    },

                    init() {
                        this.filterSemestres();
                    },

                    formatDate(date) {
                        return new Date(date).toLocaleDateString('fr-FR');
                    },

                    openModal() {
                        this.form = {
                            name: '',
                            date_debut: '',
                            date_fin: ''
                        };
                        this.showModal = true;
                    },

                    closeModal() {
                        this.showModal = false;
                    },

                    filterSemestres() {
                        const term = this.searchTerm.toLowerCase();
                        this.filteredSemestres = this.semestres.filter(semestre =>
                            semestre.name.toLowerCase().includes(term)
                        );
                        this.totalPages = Math.ceil(this.filteredSemestres.length / this.semestresPerPage);
                        this.currentPage = 1;
                    },

                    get paginatedSemestres() {
                        const start = (this.currentPage - 1) * this.semestresPerPage;
                        return this.filteredSemestres.slice(start, start + this.semestresPerPage);
                    },

                    async addSemestre() {
                        this.isLoading = true;
                        const response = await fetch('{{ route('semestre.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                ...this.form,
                                annee_academique_id: {{ $anneacademique->id }}
                            }),
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.semestres.push(data.semestre);
                            this.filterSemestres();
                            this.closeModal();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de l\'ajout.',
                            });
                        }
                        this.isLoading = false;
                    },

                    async deleteSemestre(id) {
                        const confirmed = confirm("Voulez-vous vraiment supprimer ce semestre ?");
                        if (!confirmed) return;

                        try {
                            const url = `{{ route('semestre.destroy', ['id' => '__ID__']) }}`.replace('__ID__', id);
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            });

                            if (response.ok) {
                                this.semestres = this.semestres.filter(semestre => semestre.id !== id);
                                this.filterSemestres();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Semestre supprimé',
                                    timer: 1500,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Échec de la suppression',
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur réseau',
                            });
                        }
                    },

                    async toggleCloture(semestre) {
                        const response = await fetch(`{{ route('semestre.toggleCloture') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: semestre.id
                            })
                        });

                        if (response.ok) {
                            semestre.cloture = !semestre.cloture; // <-- mise à jour locale
                        } else {
                            alert('Erreur lors du changement de statut.');
                        }
                    }

                };
            }
        </script>
    </div>
@endsection
