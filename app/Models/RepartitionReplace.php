<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepartitionReplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'examen_id',
        'annee_academique_id',
    ];
}
