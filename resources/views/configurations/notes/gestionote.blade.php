@extends('layouts.app')

@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="classeByAnneeAcademique()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            CLASSES DE L'ANNÉE ACADÉMIQUE EN COURS
                        </h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="mb-4">
                        <input type="text" class="form-control form-control-solid w-250px"
                            placeholder="Rechercher une classe..." x-model="searchTerm" @input="filterClasses">
                    </div>

                    <div class="row">
                        <template x-for="classe in filteredClasses" :key="classe.id">
                            <div class="col-md-2 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                        style="height: 150px;">
                                        <!-- SVG dossier scolaire -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#0d6efd"
                                            class="bi bi-folder2-open" viewBox="0 0 16 16">
                                            <path
                                                d="M1.643 4.278A.5.5 0 0 0 1 4.75v6.5a.5.5 0 0 0 .5.5h1.086l.58 1.161a.5.5 0 0 0 .447.276h8.774a.5.5 0 0 0 .49-.598l-.5-2.5a.5.5 0 0 0-.49-.402H3.272L2.64 5.89A.5.5 0 0 0 2.18 5.5H1.75a.5.5 0 0 0-.107.01Z" />
                                            <path
                                                d="M11.5 2h-5a.5.5 0 0 0-.416.223l-1.276 1.916-.002.003-.001.001a.5.5 0 0 1-.416.22H1.75a.5.5 0 0 0-.5.5V4.5h1.086a1.5 1.5 0 0 1 1.342.827L4.72 8h7.528l.5-2.5A.5.5 0 0 0 12.25 5h-.527a.5.5 0 0 1-.445-.276l-1.161-2.322A.5.5 0 0 0 10 2.5h1.5Z" />
                                        </svg>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-primary fw-bold" x-text="classe.classe.name"></h5>
                                        <p class="mb-1">
                                            <strong>Niveau :</strong> <span x-text="classe.niveau.name"></span>
                                        </p>

                                        <div class="d-flex justify-content-end">
                                            <a :href="'{{ route('configurationnote.classe.gestion.note', ['id' => '__ID__']) }}'
                                            .replace('__ID__',
                                                classe.id)"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-cogs"></i> Gérer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function classeByAnneeAcademique() {
            return {
                classes: @json($classes),
                filteredClasses: [],
                searchTerm: '',

                init() {
                    this.filteredClasses = this.classes;
                },

                filterClasses() {
                    this.filteredClasses = this.classes.filter(classe => {
                        return classe.classe.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            classe.niveau.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            classe.annee_academique.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                    });
                },


            };
        }
    </script>
@endsection
