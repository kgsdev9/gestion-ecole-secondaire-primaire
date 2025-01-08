<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_eleve',
        'id_matiere',
        'note',
        'type_composition',
        'date_composition',
        'anneacademique_id'
    ];

}
