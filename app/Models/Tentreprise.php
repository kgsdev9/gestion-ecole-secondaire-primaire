<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tentreprise extends Model
{
    use HasFactory;
    protected $fillable = [
        'libtiers',
        'email',
        'telephone',
        'adresse',
        'fax',
        'logo',
        'numero_registre',
        'user_id',
    ];
}
