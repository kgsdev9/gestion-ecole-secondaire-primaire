@extends('layouts.app')

@section('title', 'Recherche de Moyenne')

@section('content')
<div class="app-content flex-column-fluid" x-data="convocationForm()" x-init="init()">
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-body p-10">

                <h2 class="fs-2x fw-bold text-center mb-10">Impression de convocation</h2>

                <div class="row justify-content-center">
                    <div class="col-md-6">

                            <div class="mb-5">
                                <label for="code" class="form-label">Code de l'examen</label>
                                <input type="text" x-model="code" id="code" class="form-control" placeholder="EX2024A" required>
                            </div>

                            <div class="mb-5">
                                <label for="matricule" class="form-label">Matricule de l’élève</label>
                                <input type="text" x-model="matricule" id="matricule" class="form-control" placeholder="MTR123456" required>
                            </div>

                            <div class="text-center">
                                <button type="button" @click="imprimerConvocation()" class="btn btn-primary">
                                    Rechercher
                                </button>
                            </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function convocationForm() {
        return {
            code: '',
            matricule: '',

            init() {
                
            },

            async imprimerConvocation() {
                const formData = new FormData();
                formData.append('code', this.code);
                formData.append('matricule', this.matricule);

                try {
                    const response = await fetch('{{ route('configuration.convocation.examens.print') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error('Erreur lors de la génération');
                    }

                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    window.open(url, '_blank');
                } catch (error) {
                    alert('Erreur : impossible d\'imprimer la convocation.');
                    console.error(error);
                }
            }

        }
    }
</script>
@endsection
