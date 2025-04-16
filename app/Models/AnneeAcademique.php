<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_debut',
        'date_fin'
    ];


    public static function anneeAcademiqueEnCours()
    {
        return self::whereDate('date_debut', '<=', Carbon::today())
            ->whereDate('date_fin', '>=', Carbon::today())
            ->first(); // On suppose qu'il y a une seule année académique en cours
    }

    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }
}
