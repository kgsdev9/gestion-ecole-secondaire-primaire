<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'matiere_id',
        'note',
        'type_composition',
        'date_composition',
        'anneacademique_id'
    ];

}
