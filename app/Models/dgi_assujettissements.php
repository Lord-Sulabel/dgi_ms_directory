<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class dgi_assujettissements extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'fk_repertoire',
        'dateDebut',
        'dateFin',
        'etat',
        'dateCreate',
        'agentCreate',
        'dateUpdate',
        'agentUpdate',
        'dateDelete',
        'agentDelete',
        'fk_impots',
        'fk_nature_impots',
    ];

    
}


