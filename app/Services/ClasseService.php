<?php

namespace App\Services;

use App\Models\Classe;
use App\Services\AnneeAcademiqueService;

class ClasseService
{

    protected $classeservice;
    protected $anneAcademiqueService;
    public function __construct(Classe $classeservice, AnneeAcademiqueService $anneAcademiqueService)
    {
        $this->classeservice = $classeservice;
        $this->anneAcademiqueService = $anneAcademiqueService;
    }

    /**
     * recuperation de la classe par l'id de la classe.
     *
     * @param int $id de la classse
     */
    public function getClasseById($classeid): string
    {
        return $this->classeservice->where('id', $classeid)->first();
    }
    // public function getAnnneAcademiqueClasse()
    // {
    //     return  $this->classeservice
    //         ->where('anneeacademique_id', $this->anneAcademiqueService->getAnneeActive()->id)
    //         ->get();
    // }

    public function getAnneeAcademiqueClasse()
    {
        return $this->classeservice
            ->newQuery()
            ->with(['niveau', 'salle'])
            ->where('anneeacademique_id', $this->anneAcademiqueService->getAnneeActive()->id)
            ->get();
    }
}
