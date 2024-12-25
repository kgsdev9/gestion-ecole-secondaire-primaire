<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelleproduct',
        'prixachat',
        'prixvente',
        'codeproduct',
        'qtedisponible',
        'tcategorieproduct_id',
        'description',
        'image'
    ];

    public function category(){
        return $this->belongsTo(TCategorieProduct::class, 'tcategorieproduct_id');
    }
}
