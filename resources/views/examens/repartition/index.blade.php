@extends('layouts.app')
@section('title', 'Liste des répartitions d\'examens')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="repartitionSearch()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            GESTION DES RÉPARTITIONS DES EXAMENS
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
                                        placeholder="Rechercher" x-model="searchTerm" @input="filterRepartition">
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
                                                <th class="min-w-125px">Type</th>
                                                <th class="min-w-125px">Classe</th>
                                                <th class="min-w-125px">Année académique</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <template x-for="repartition in paginatedRepartitions" :key="repartition.id">
                                                <tr>
                                                    <td x-text="repartition.examen?.name ?? '-'"></td>
                                                    <td x-text="repartition.examen?.type_examen?.name ?? '-'"></td>
                                                    <td x-text="repartition.examen?.classe?.name ?? '-'"></td>
                                                    <td x-text="repartition.annee_academique?.name ?? '-'"></td>

                                                    <td class="text-end">

                                                        <!-- Bouton "Créer la répartition" affiché seulement si l'examen n'est pas clôturé -->
                                                        <template x-if="repartition.examen?.cloture != 1">
                                                            <a :href="`{{ route('examens.repartition.examens.create', ['id' => '__ID__']) }}`
                                                            .replace('__ID__', repartition.examen?.id)"
                                                                class="btn btn-warning btn-sm" title="Créer la répartition">
                                                                <i class="fa fa-random"></i>
                                                            </a>
                                                        </template>

                                                        &nbsp;&nbsp;

                                                        <!-- Bouton "Visualisation de la répartition" (toujours affiché) -->
                                                        <a :href="`{{ route('examens.repartition.show', ['repartition' => '__ID__']) }}`
                                                        .replace('__ID__', repartition.examen?.id)"
                                                            class="btn btn-light btn-sm"
                                                            title="Visualisation de la répartition">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <button @click="printRepartition(repartition.examen.code)"
                                                        class="btn btn-sm btn-light-primary">
                                                        <i class="fa fa-print"></i> 
                                                    </button>


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

    </div>

    <script>
        function repartitionSearch() {
            return {
                searchTerm: '',
                repartitions: @json($repartitions),
                filteredRepartitions: [],
                currentPage: 1,
                repartitionsPerPage: 10,
                isLoading: false,

                filterRepartition() {
                    this.filteredRepartitions = this.repartitions.filter(r =>
                        (r.examen?.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                },

                get paginatedRepartitions() {
                    let start = (this.currentPage - 1) * this.repartitionsPerPage;
                    return this.filteredRepartitions.slice(start, start + this.repartitionsPerPage);
                },

                printRepartition(codeexamen) {


                    // Vérifier si toutes les valeurs sont renseignées
                    if (!codeexamen ) {
                        alert("Code de l'examan requis.");
                        return;
                    }

                    const formData = new FormData();
                    formData.append('codeexamen', codeexamen);

                    fetch('{{ route('examens.impression.repartition.examen') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.url) {
                                window.open(data.url, '_blank');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.message || "Impossible de générer le bulletin.",
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de l’envoi :', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur serveur ou réseau.',
                            });
                        });
                },

                init() {
                    this.filterRepartition();
                },
            }
        }
    </script>
@endsection
