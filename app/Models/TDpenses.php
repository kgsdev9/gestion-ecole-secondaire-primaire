<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDpenses extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'description',
        'montant',
        'mode_paiement',
        'notes',
    ];
}
