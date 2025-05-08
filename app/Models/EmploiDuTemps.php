<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $fillable = ['matiere_id', 'heure_debut', 'heure_fin', 'classe_id', 'jour_id', 'anneeacademique_id'];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }
}
