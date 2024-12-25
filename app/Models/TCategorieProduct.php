<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCategorieProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'libellecategorieproduct',
    ];
}
