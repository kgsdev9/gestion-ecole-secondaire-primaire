<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TventeDirect extends Model
{
    use HasFactory;
    protected $fillable = [
        'numvte',
        'codeclient',
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'montantht',
        'montanttc',
        'montanttva',
        'montantadsci',
    ];
}
