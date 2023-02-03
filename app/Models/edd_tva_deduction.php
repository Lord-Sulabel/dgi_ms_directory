<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class edd_tva_deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nif',
        'date_exigibilite',
        'fournisseur_nom',
        'fournisseur_nif',
        'facture_numero',
        'facture_date',
        'montant',
    ];



    public function List($edd_tva_id){

        $list = DB::table('edd_tva_deductions')
                ->select('*')
                ->where("fk_edd_tva","=",$edd_tva_id)
                ->get();
        return $list;
    }






}
