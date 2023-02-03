<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ipr_ier extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nif',
        'type',
        'valeur',
        'date_debut',
        'date_fin',

    ];



    public function List(/*$edd_tva_id*/){

        /*$list = DB::table('edd_tva_deductions')
                ->select('*')
                ->where("fk_edd_tva","=",$edd_tva_id)
                ->get();
        return $list;*/
    }


}
