<?php

namespace App\Http\Controllers\Assujettissement;


use App\Models\Assujettissements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\HasApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Models\remote_server;

use Validator;
use DB;


class MainController extends Controller
{
    use HasApiResponse;


    public function Assujettissement__New(Request $request){
        /*
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
        */
    }


    public function Create(Request $request){
        
        $response = "";
        $validator = Validator::make($request->all(), [
            'fk_contribuable' => 'required|min:5',
            'date_debut'   => 'required|date',
            'fk_impot'=> 'required',
            'fk_acte_generateur'  => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        //..........................................................
        $fk_contribuable        = $request['fk_contribuable'];
        $date_debut             = $request['date_debut'];
        $fk_impots              = $request['fk_impot'];
        $fk_actes_generateurs   = $request['fk_acte_generateur'];
        
        $obj = new assujettissements;
        
        $obj->fk_contribuable   = $fk_contribuable;
        $obj->date_debut        = $date_debut;
        $obj->fk_impots          = $fk_impots;
        $obj->fk_actes_generateurs = $fk_actes_generateurs;
        $saved = $obj->save();
                    
        if($saved){
            return response( ["status"=>true, "message"=>"saved successfully"], 200)->header('Content-Type', 'text/JSON');

        }else{
            return response( ["status"=>false,"message"=>"saving failure"], 200)->header('Content-Type', 'text/JSON');
        
        }

            
        
        
        
        
        $tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        print_r($tojson);
        
    }



    public function List(Request $request){


        $impot = $request['impot'];
        $fk_actes_generateurs = $request['acte_generateur'];
        

        $response = array();
        $post   = new assujettissements;
        $values = $post;

        if(isset($impot)){
            $values = $values->where("fk_impots","=",$impot);
        }

        if(isset($fk_actes_generateurs)){
            $values = $values->where("fk_actes_generateurs","=",$fk_actes_generateurs);
        }

        $values = $values->get();

        
        $values = json_decode(json_encode($values));

        //print_r($values);
        //print_r($values);
        $all_nifs = array();
        $all_actes = array();

        if(is_array($values)){
            foreach($values as $val){
                $nif = $val->fk_contribuable;
                if(!in_array($nif,$all_nifs)){
                    $all_nifs[] = $nif;
                }

                $acte = $val->fk_actes_generateurs;
                if(!in_array($acte,$all_actes)){
                    $all_actes[] = $acte;
                }
            }

            
            
            $all_nifs = base64_encode(json_encode($all_nifs));
            $remote_server = new remote_server;
            $all_contribuables = $remote_server->GetData("http://localhost/dgi_ms_gestion_contribuable/public/api/for_nifs_v2/".$all_nifs);
            $all_contribuables = json_decode($all_contribuables);
            unset($all_nifs);

            //print_r($all_contribuables);
            
            $all_actes = base64_encode(json_encode($all_actes));
            $remote_server = new remote_server;
            $all_actes_generateurs = $remote_server->GetData("http://localhost/dgi_ms_gestion_impots/public/api/actes_for_ids_v2/".$all_actes); 
            $all_actes_generateurs = json_decode($all_actes_generateurs);
            unset($all_actes);
            unset($remote_server);

            //print_r($all_actes_generateurs);

            foreach($values as $val){
                $nif = $val->fk_contribuable;
                $acte = $val->fk_actes_generateurs;
                $val->contribuable    = $all_contribuables->$nif;
                $val->acte_generateur = $all_actes_generateurs->$acte;
                unset($val->fk_contribuable);
                unset($val->fk_actes_generateurs);
            }
        }
        
        $values = json_encode($values,JSON_UNESCAPED_UNICODE);
        return response($values, 200)->header('Content-Type', 'text/JSON');       
    }


    public function Update($id, request $request){

        $obj = assujettissements ::find($id);
        if($obj){   

            $validator = Validator::make($request->all(), [
                'fk_contribuable' => 'required|min:5',
                'date_debut'   => 'required|date',
                'fk_impot'=> 'required',
                'fk_acte_generateur'  => 'required',
            ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
                
            $fk_contribuable        = $request['fk_contribuable'];
            $date_debut             = $request['date_debut'];
            $fk_impots              = $request['fk_impot'];
            $fk_actes_generateurs   = $request['fk_acte_generateur'];
            

            
            $obj->fk_contribuable       = $fk_contribuable;
            $obj->date_debut            = $date_debut;
            $obj->fk_impots             = $fk_impots;
            $obj->fk_actes_generateurs  = $fk_actes_generateurs;
        
            $saved = $obj->save();
                        
            if($saved){
                return response( ["status"=>true, "message"=>"saved successfully"], 200)->header('Content-Type', 'text/JSON');

            }else{
                return response( ["status"=>false,"message"=>"saving failure"], 200)->header('Content-Type', 'text/JSON');
            
            }
            
        }else{

            return response( ["status"=>false,"message"=>"saving failure","detail"=>"reccord not found"], 200)->header('Content-Type', 'text/JSON');
        
        }   
        
        
        //$tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        //print_r($tojson);
    }
    
    public function View($id){
        $obj    = new assujettissements;      
        $values = $obj->find($id);
        $values = json_encode($values,JSON_UNESCAPED_UNICODE);
    
        return response($values, 200)->header('Content-Type', 'text/JSON');
    }


/*
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

        
    }*/



    public function Terminate($id, request $request){

        $obj = assujettissements ::find($id);
        if($obj){   

            $validator = Validator::make($request->all(), [
                
            ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
                
            $obj->date_fin  = date("Y-m-d");
        
            $saved = $obj->save();
                        
            if($saved){
                return response( ["status"=>true, "message"=>"saved successfully"], 200)->header('Content-Type', 'text/JSON');

            }else{
                return response( ["status"=>false,"message"=>"saving failure"], 200)->header('Content-Type', 'text/JSON');
            
            }
            
        }else{

            return response( ["status"=>false,"message"=>"saving failure","detail"=>"reccord not found"], 200)->header('Content-Type', 'text/JSON');
        
        }   
        
        
        //$tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        //print_r($tojson);
    }













    
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200)
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

    
    

    
    
}
