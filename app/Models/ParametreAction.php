<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreAction extends Model
{
    use HasFactory;

    protected $fillable = ['classe_id', 'niveau_id', 'anneeacademique_id'];
}
