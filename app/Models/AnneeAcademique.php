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
        'date_fin',
        'cloture'
    ];


    public static function anneeAcademiqueEnCours()
    {
        return self::whereDate('date_debut', '<=', Carbon::today())
            ->whereDate('date_fin', '>=', Carbon::today())
            ->first();
    }

    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }

    public function scopeActuelle($query)
    {
        return $query->where('cloturee', false)->latest()->first();
    }
    // $annee = AnneeAcademique::actuelle();
    public function anneeAcademiqueActuelle()
    {
        $now = now();
        $anneeDebut = $now->month >= 9 ? $now->year : $now->year - 1;
        return $anneeDebut . '-' . ($anneeDebut + 1);
    }
}
