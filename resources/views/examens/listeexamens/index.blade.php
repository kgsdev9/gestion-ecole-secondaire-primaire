@extends('layouts.app')
@section('title', 'Liste des examens')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="examenSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES EXAMENS
                        </h1>
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterExamen">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        @click="showModal = true">
                                        <i class="fa fa-add"></i> Création
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
                                                <th class="min-w-125px">Nom Examen</th>

                                                <th class="min-w-125px">Type Examen</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Date de début</th>
                                                <th class="min-w-125px">Date de fin</th>
                                                <th class="min-w-125px">Clôture</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="examen in paginatedExamens" :key="examen.id">
                                                <tr>
                                                    <td x-text="examen.name"></td>

                                                    <td x-text="examen.type_examen.name"></td>
                                                    <td x-text="examen.annee_academique.name"></td>
                                                    <td x-text="examen.classe.name"></td>
                                                    <td x-text="new Date(examen.date_debut).toLocaleDateString('fr-FR')">
                                                    </td>
                                                    <td x-text="new Date(examen.date_fin).toLocaleDateString('fr-FR')"></td>
                                                    <td x-text="examen.cloture ? 'Oui' : 'Non'"></td>
                                                    <td class="text-end d-flex justify-content-start">
                                                        <button @click="openModal(examen)"
                                                            class="btn btn-primary btn-sm mx-2">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteExamen(examen.id)"
                                                            class="btn btn-danger btn-sm mx-2">
                                                            <i class="fa fa-trash"></i>
                                                        </button>


                                                        {{-- <a :href="`{{ route('examens.programme.examens', ['id' => '__ID__']) }}`
                                                        .replace(
                                                            '__ID__', examen.id)"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-calendar-check"></i>
                                                        </a>
                                                        &nbsp; &nbsp; --}}
                                                        {{-- <a :href="`{{ route('examens.create.repartition', ['id' => '__ID__']) }}`
                                                        .replace
                                                            ('__ID__', examen.id)"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fa fa-sitemap"></i>
                                                        </a> --}}

                                                        &nbsp; &nbsp;
                                                        <a :href="`{{ route('examens.save.moyenne', ['id' => '__ID__']) }}`
                                                        .replace
                                                            ('__ID__', examen.id)"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fa fa-calculator"></i>
                                                        </a>



                                                    </td>

                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour la création et la modification -->
        <template x-if="showModal">
            <div class="modal fade show d-block" tabindex="-1" aria-modal="true" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" x-text="isEdite ? 'Modification' : 'Création'"></h5>
                            <button type="button" class="btn-close" @click="hideModal()"></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="submitForm">
                                <div class="row g-3"> <!-- row avec espacement entre colonnes -->

                                    <!-- Nom -->
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">Nom de l'examen</label>
                                        <input type="text" id="nom" class="form-control" x-model="formData.nom"
                                            required>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-6">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea id="description" class="form-control" x-model="formData.description" required></textarea>
                                    </div>

                                    <!-- Type examen -->
                                    <div class="col-md-6">
                                        <label for="typeexamen_id" class="form-label">Type Examen</label>
                                        <select id="typeexamen_id" x-model="formData.typeexamen_id" class="form-select"
                                            required>
                                            <option value="">Type examen</option>
                                            @foreach ($typexamen as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Année académique -->
                                    <div class="col-md-6">
                                        <label for="anneeacademique_id" class="form-label">Année académique</label>
                                        <select id="anneeacademique_id" x-model="formData.anneeacademique_id"
                                            class="form-select" required>
                                            <option value="">Année académique</option>
                                            @foreach ($anneAcademique as $anne)
                                                <option value="{{ $anne->id }}">{{ $anne->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Classe -->
                                    <div class="col-md-6">
                                        <label for="classe_id" class="form-label">Classe</label>
                                        <select id="classe_id" x-model="formData.classe_id" class="form-select" required>
                                            <option value="">Sélectionner une classe</option>
                                            @foreach ($classe as $classroom)
                                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date de début -->
                                    <div class="col-md-6">
                                        <label for="date_debut" class="form-label">Date de début</label>
                                        <input type="date" id="date_debut" class="form-control"
                                            x-model="formData.date_debut" required>
                                    </div>

                                    <!-- Date de fin -->
                                    <div class="col-md-6">
                                        <label for="date_fin" class="form-label">Date de fin</label>
                                        <input type="date" id="date_fin" class="form-control"
                                            x-model="formData.date_fin" required>
                                    </div>

                                    <!-- Clôture -->
                                    <div class="col-md-6 d-flex align-items-center mt-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="cloture" x-model="formData.cloture"
                                                class="form-check-input">
                                            <label for="cloture" class="form-check-label ms-2">Clôturé</label>
                                        </div>
                                    </div>

                                    <!-- Bouton submit -->
                                    <div class="col-md-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary"
                                            x-text="isEdite ? 'Mettre à jour' : 'Enregistrer'"></button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </template>

    </div>

    <script>
        function examenSearch() {
            return {
                searchTerm: '',
                examens: @json($listeexamens),
                filteredExamens: [],
                currentPage: 1,
                examensPerPage: 10,
                totalPages: 0,
                isLoading: false,
                showModal: false,
                isEdite: false,
                formData: {
                    nom: '',
                    description: '',
                    typeexamen_id: '',
                    anneeacademique_id: '',
                    classe_id: '',
                    date_debut: '',
                    date_fin: '',
                    cloture: false,
                },
                currentExamen: null,
                typeExamens: @json($typexamen),
                anneeAcademiques: @json($anneAcademique),
                classes: @json($classe),

                hideModal() {
                    this.showModal = false;
                    this.currentExamen = null;
                    this.resetForm();
                    this.isEdite = false;
                },

                formatDate(date) {
                    if (!date) return '';
                    let d = new Date(date);
                    return d.toISOString().slice(0, 16); // Format "YYYY-MM-DDTHH:mm"
                },

                openModal(examen = null) {
                    this.isEdite = examen !== null;
                    if (this.isEdite) {
                        this.currentExamen = {
                            ...examen
                        };
                        this.formData = {
                            nom: this.currentExamen.name,
                            description: this.currentExamen.description,
                            typeexamen_id: this.currentExamen.typeexamen_id,
                            anneeacademique_id: this.currentExamen.anneeacademique_id,
                            classe_id: this.currentExamen.classe_id,
                            date_debut: this.currentExamen.date_debut, // Pas besoin de reformatter
                            date_fin: this.currentExamen.date_fin, // Pas besoin de reformatter
                            cloture: this.currentExamen.cloture,
                        };

                    } else {
                        this.resetForm();
                    }
                    this.showModal = true;
                },

                resetForm() {
                    this.formData = {
                        nom: '',
                        description: '',
                        typeexamen_id: '',
                        anneeacademique_id: '',
                        classe_id: '',
                        date_debut: '',
                        date_fin: '',
                        cloture: false,
                    };
                },

                async submitForm() {
                    this.isLoading = true;

                    if (!this.formData.nom.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Le nom est requis.',
                        });
                        this.isLoading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('nom', this.formData.nom);
                    formData.append('description', this.formData.description);
                    formData.append('typeexamen_id', this.formData.typeexamen_id);
                    formData.append('anneeacademique_id', this.formData.anneeacademique_id);
                    formData.append('classe_id', this.formData.classe_id);
                    formData.append('date_debut', this.formData.date_debut);
                    formData.append('date_fin', this.formData.date_fin);
                    formData.append('cloture', this.formData.cloture);
                    if (this.currentExamen) {
                        formData.append('examen_id', this.currentExamen.id);
                    }

                    try {
                        const response = await fetch('{{ route('examens.gestion.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const examen = data.examen;

                            if (examen) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Examen enregistré avec succès.',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });

                                if (this.isEdite) {
                                    const index = this.examens.findIndex(e => e.id === examen.id);
                                    if (index !== -1) {
                                        this.examens[index] = examen;
                                    }
                                } else {
                                    this.examens.push(examen);
                                    this.examens.sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut));
                                }

                                this.filterExamen();
                                this.resetForm();
                                this.hideModal();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur lors de l\'enregistrement.',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur serveur.',
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },

                deleteExamen(id) {
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: 'Cela supprimera définitivement cet examen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Non',
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/examens/gestion/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                });

                                if (response.ok) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Examen supprimé avec succès.',
                                    });

                                    this.examens = this.examens.filter(examen => examen.id !== id);
                                    this.filterExamen();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur lors de la suppression.',
                                    });
                                }
                            } catch (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur serveur.',
                                });
                            }
                        }
                    });
                },

                filterExamen() {
                    this.filteredExamens = this.examens.filter(examen => {
                        return examen.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            examen.description.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                },

                get paginatedExamens() {
                    let start = (this.currentPage - 1) * this.examensPerPage;
                    let end = start + this.examensPerPage;
                    return this.filteredExamens.slice(start, end);
                },

                init() {
                    this.filterExamen();
                    this.isLoading = false;
                },
            };
        }
    </script>
@endsection
