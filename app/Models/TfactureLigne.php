<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TfactureLigne extends Model
{
    use HasFactory;
    protected $fillable = [
        'tproduct_id',
        'codecommade',
        'codefacture',
        'numvente',
        'designation',
        'quantite',
        'prix_unitaire',
        'remise',
        'montant_ht',
        'montant_tva',
        'montant_ttc'
    ];



    public function product()
    {
        return $this->belongsTo(TProduct::class, 'tproduct_id', 'id');
    }

    // Relation inverse avec la facture
    public function facture()
    {
        return $this->belongsTo(TFacture::class, 'facture_id');
    }

    // Calcul des montants pour la ligne
   
}
