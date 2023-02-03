<?php

namespace App\Http\Controllers\Assujettissement;


use App\Models\Ipr_ier;
use App\Models\Remote_server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\HasApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Validator;
use DB;


class Ipr_ierController extends Controller
{
    use HasApiResponse;

/*
    public function Assujettissement__New(Request $request){
        $response = "";
        $validator = Validator::make($request->all(), [
            'date_exigibilite' => 'required',
            'nif'   => 'required',
            'fournisseur_nom'=> 'required',
            'fournisseur_nif'  => 'required',
            'facture_numero'   => 'required',
            'facture_date'   => 'required',
            'montant'   => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        //..........................................................
        $date_exigibilite   = $request['date_exigibilite'];
        $nif                = $request['nif'];
        $exist_edd = $this->EDD_TVA__Exist($nif, $date_exigibilite);

        if(!$exist_edd){
            $created = $this->EDD_TVA__Create($date_exigibilite,$nif);
            if(!$created){
                $response = [
                    'status' => false,
                    'message' =>  "Impossible créer la declaration",
                    'data' => [
                        "date_exigibilite"  =>$date_exigibilite,
                        "nif"               =>$nif,
                    ],
                ];
            }
        }

        if($exist_edd || $created){
            //$EDD_TVA__Id = $this->EDD_TVA__GetId($nif, $date_exigibilite) ;
            $obj = new edd_tva;
            $id = $obj->GetId($nif, $date_exigibilite);
            $resp   = json_decode($id);
            $tsize  = sizeof($resp);
            if($tsize > 0){
                $obj = $resp[0];
                $EDD_TVA__Id = $obj->id;
            }

            $data = [
                "date_exigibilite"  =>$date_exigibilite,
                "nif"               =>$nif,
                "fournisseur_nom"   =>$request['fournisseur_nom'],
                "fournisseur_nif"   =>$request['fournisseur_nif'],
                "facture_numero"    =>$request['facture_numero'],
                "facture_date"      =>$request['facture_date'],
                "montant"           =>$request['montant'],
            ];

            $saved = $this->EDD_TVA_DEDUCTION__Create($EDD_TVA__Id,$data);
                       
            if($saved){

                $response = [
                    'status'  => true,
                    'message' =>  "Données enregistrés avec succès",
                    'data' => $data,
                ];

            }else{

                 $response = [
                    'status'  => false,
                    'message' =>  "impossible d'enregistrer",
                    'data' => $data,
                ];
            }

            
        }
        
        
        
        $tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        print_r($tojson);
    }

*/


    public function List(Request $request){

        
        $obj    = new ipr_ier;
        $values = $obj
            //->where("fk_titre_perception","=",$titre)
            ->get();

           
            $values = json_encode($values);
            $values = json_decode($values);
    
            if(is_array($values)){
                $nifs = [];
                foreach($values as $val){
                    $nifs[] = $val->nif;
                }
                $base_url = "http://localhost/dgi_ms_gestion_contribuable/public/api/for_nifs/";
                $para = json_encode($nifs);
                $para = base64_encode($para);
                $url = $base_url.$para;
                $contribuables_server = new remote_server;
                $contribuables = $contribuables_server->GetData($url);
                print_r($contribuables);

                foreach($values as $val){
                    $d_start = $val->date_debut;
                    $d_end   = $val->date_fin;

                    $t_start = strtotime($d_start);
                    $t_end   = strtotime($d_end);
                    $t_now   = strtotime(date("Y-m-d"));
                    
                    if($t_now > $t_end){
                        $val->status = "Cloturé";

                    }elseif($t_start > $t_now){
                        $val->status = "En attente";

                    }elseif($t_now >= $t_start){
                        $val->status = "En Cours";

                    }


                }
            }
            

        $values = json_encode($values,JSON_UNESCAPED_UNICODE);
        return response($values, 200)->header('Content-Type', 'text/JSON');


        
    }


    public function Impot_List(Request $request){

        $response = array();

        //$date_exigibilite   = $request['date_exigibilite'];
       // $nif   = $request['nif'];

        $list = DB::table('impots')
                ->select('*');
        
        $list_b = $list->get();
        $response = json_decode($list_b); 
        
        $is_api_request = $request->route()->getPrefix() === 'api';
        if($is_api_request){
            $tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
            print_r($tojson);

        }else{

           return view('IMPOTS/list',['response' => $response] );
        }

        
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'status' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    
    protected function EDD_TVA_DEDUCTION__Create($edd_tva_id,$data){
        $saved = false;
        
        $post       = new edd_tva_deduction;

        $fournisseur_nom    = $data['fournisseur_nom'];
        $fournisseur_nif    = $data['fournisseur_nif'];
        $facture_numero     = $data['facture_numero'];
        $facture_date       = $data['facture_date'];
        $montant            = $data['montant']; 

        $post->fk_edd_tva       = $edd_tva_id;
        $post->fournisseur_nom  = $fournisseur_nom;
        $post->fournisseur_nif  = $fournisseur_nif;
        $post->facture_numero   = $facture_numero;
        $post->facture_date     = $facture_date;
        $post->montant          = $montant;
        $saved                  = $post->save();
        
        return $saved;
    }


    protected function EDD_TVA__Create($date_exigibilite,$nif){
        $response = false;
        $post = new EDD_TVA;
        $post->date_exigibilite = $date_exigibilite;
        $post->nif              = $nif;
        $saved = $post->save();
        if($saved){
            $response = true;
        }else{
            $response = false;
        }
        return $response;
    }


    protected function EDD_TVA__Exist($nif, $date_exigibilite){

        $response = false;
        $id = DB::table('edd_tvas')
            ->select('id')
            ->where("nif","=",$nif,"and")
            ->where("date_exigibilite","=",$date_exigibilite,"and")
            ->get();

        $resp   = json_decode($id);
        $tsize  = sizeof($resp);
        if($tsize > 0){
            $response = true;
        }
        
        return $response;
    }

    
    
}
