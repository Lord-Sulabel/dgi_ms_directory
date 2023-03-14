<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class dgi_assujettissements extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_contribuable',
        'date_debut',
        'fk_impot',
        'fk_acte_generateur',
    ];

    
}


