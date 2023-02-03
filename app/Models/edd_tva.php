<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class edd_tva extends Model
{
    use HasFactory;

    protected $fillable = [
        'nif',
        'date_exigibilite',
        
    ];


    public function GetId($nif, $date_exigibilite){

        $response = 0;
        $id = DB::table('edd_tvas')
            ->select('id')
            ->where("nif","=",$nif,"and")
            ->where("date_exigibilite","=",$date_exigibilite,"and")
            ->get();       
        return $id;
    }
    
}
