@extends('layouts.app')
@section('title', 'Gestion des moyennes')
@section('content')
    <div class="app-main flex-column flex-row-fluid mt-4" x-data="moyenneManager()" x-init="init()">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading text-gray-900 fw-bold fs-3">GESTION DES MOYENNES SCOLAIRES</h1>
                    </div>
                </div>
            </div>

            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="fas fa-search position-absolute ms-5"></i>
                                    <input type="text"
                                        class="form-control form-control-solid w-250px ps-13 form-control-sm"
                                        placeholder="Rechercher un élève..." x-model="searchEleve" @input="filterEleves">
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <div>
                                        <select x-model="selectedClasseId" @change="filterEleves"
                                            class="form-select form-select-sm">
                                            <option value="">Toutes les classes</option>
                                            <template x-for="cl in classes" :key="cl.id">
                                                <option :value="cl.id" x-text="cl.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <select x-model="semestre_id" @change="filterEleves"
                                            class="form-select form-select-sm">
                                            <option value="">Tous les semestres</option>
                                            <template x-for="semestre in semestres" :key="semestre.id">
                                                <option :value="semestre.id" x-text="semestre.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <button @click="print" class="btn btn-light-primary btn-sm">
                                        <i class="fa fa-print"></i> Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body py-4">
                            <div class="table-responsive">
                                <h4 class="mb-4 fw-bold">Moyennes des élèves</h4>
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th>Élève</th>
                                            <template x-for="matiere in matieresFiltres()" :key="matiere.id">
                                                <th x-text="matiere.name"></th>
                                            </template>
                                            <th>Moyenne Générale</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="eleve in elevesFiltres" :key="eleve.id">
                                            <tr>
                                                <td x-text="eleve.nom + ' ' + eleve.prenom"></td>
                                                <template x-for="matiere in matieresFiltres()" :key="matiere.id">
                                                    <td>
                                                        <span x-text="getNote(eleve.id, matiere.id)"></span><br>
                                                        <small class="text-muted">(Rang: <span
                                                                x-text="getRang(eleve.id, matiere.id)"></span>)</small>
                                                    </td>
                                                </template>
                                                <td x-text="calculerMoyenneGenerale(eleve.id)"></td>
                                                <td>
                                                    <button @click="printBulletin(eleve.id)"
                                                        class="btn btn-sm btn-light-primary">
                                                        <i class="fa fa-print"></i> Imprimer
                                                    </button>

                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function moyenneManager() {
                return {
                    classes: @json($classes ?? []),
                    matieres: @json($matieres ?? []),
                    eleves: @json($eleves ?? []),
                    semestres: @json($semestres ?? []),
                    moyennes: @json($moyennes ?? []),
                    selectedClasseId: '',
                    semestre_id: '',
                    searchEleve: '',
                    elevesFiltres: [],

                    init() {
                        this.filterEleves();
                    },

                    filterEleves() {
                        // Si aucun semestre n’est sélectionné, on vide la liste
                        if (!this.semestre_id) {
                            this.elevesFiltres = [];
                            return;
                        }

                        let result = this.eleves;

                        if (this.selectedClasseId) {
                            result = result.filter(e => e.classe_id == this.selectedClasseId);
                        }

                        if (this.searchEleve) {
                            const term = this.searchEleve.toLowerCase();
                            result = result.filter(e => (e.nom + ' ' + e.prenoms).toLowerCase().includes(term));
                        }

                        this.elevesFiltres = result;
                    },


                    matieresFiltres() {
                        if (!this.semestre_id) {
                            return this.matieres;
                        }

                        const matieresIdAvecNotes = this.moyennes
                            .filter(m => m.semestre_id == this.semestre_id)
                            .map(m => m.matiere_id);

                        const uniques = [...new Set(matieresIdAvecNotes)];

                        return this.matieres.filter(m => uniques.includes(m.id));
                    },

                    getNote(eleveId, matiereId) {
                        const moyenne = this.moyennes.find(m =>
                            m.eleve_id === eleveId &&
                            m.matiere_id === matiereId &&
                            (this.semestre_id ? m.semestre_id == this.semestre_id : true)
                        );
                        return moyenne ? Number(moyenne.moyenne).toFixed(2) : '-';
                    },

                    getRang(eleveId, matiereId) {
                        const elevesClasse = this.elevesFiltres.map(e => e.id);
                        const moyennesMatiere = this.moyennes
                            .filter(m =>
                                m.matiere_id === matiereId &&
                                (this.semestre_id ? m.semestre_id == this.semestre_id : true) &&
                                elevesClasse.includes(m.eleve_id)
                            );

                        // Tri décroissant des moyennes
                        moyennesMatiere.sort((a, b) => b.moyenne - a.moyenne);

                        for (let i = 0; i < moyennesMatiere.length; i++) {
                            if (moyennesMatiere[i].eleve_id === eleveId) {
                                return i + 1;
                            }
                        }

                        return '-';
                    },

                    calculerMoyenneGenerale(eleveId) {
                        const moyennesEleve = this.moyennes.filter(m =>
                            m.eleve_id === eleveId &&
                            (this.semestre_id ? m.semestre_id == this.semestre_id : true)
                        );

                        if (moyennesEleve.length === 0) return '-';

                        let total = 0;
                        let count = 0;
                        this.matieresFiltres().forEach(matiere => {
                            const note = moyennesEleve.find(m => m.matiere_id === matiere.id);
                            if (note) {
                                total += Number(note.moyenne);
                                count++;
                            }
                        });

                        return count > 0 ? (total / count).toFixed(2) : '-';
                    },

                    printBulletin(eleveId) {

                        const selectedClasseId = this
                            .selectedClasseId; // Assurez-vous que cette variable est bien définie dans votre scope
                        const semestreId = this.semestre_id; // Idem pour semestre_id


                        // Vérifier si toutes les valeurs sont renseignées
                        if (!selectedClasseId || !semestreId) {
                            alert("Veuillez renseigner toutes les informations : classe, semestre et élève.");
                            return; // Empêche l'envoi si des données sont manquantes
                        }

                        const formData = new FormData();
                        formData.append('eleve_id', eleveId);
                        formData.append('semestre_id', this.semestre_id);
                        formData.append('classe_id', this
                            .selectedClasseId);





                        fetch('{{ route('gestionmoyenne.print.bulletin') }}', {
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
                    }





                }
            }
        </script>
    </div>
@endsection
