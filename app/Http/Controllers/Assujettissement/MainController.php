<?php

namespace App\Http\Controllers\Assujettissement;


use App\Models\dgi_assujettissements;

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


    public function Create(Request $request){
        
        $response = "";
        $validator = Validator::make($request->all(), [
            //'fk_repertoire' => 'required|min:5',
            //'dateDebut'   => 'required|date',
            //'fk_natureImpots'=> 'required',
            //'impots'  => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        //..........................................................
        $obj = new dgi_assujettissements;

        $obj->fk_repertoire     = $request['fk_repertoire'];
        $obj->dateDebut         = $request['dateDebut'];
        $obj->fk_natureImpots   = $request['fk_natureImpots'];
        $obj->fk_impots         = $request['fk_impots'];
        
        $saved = $obj->save();
                    
        if($saved){
            return response( ["status"=>true, "message"=>"saved successfully"], 200)->header('Content-Type', 'text/JSON');

        }else{
            return response( ["status"=>false,"message"=>"saving failure"], 200)->header('Content-Type', 'text/JSON');
        
        }

            
        
        
        
        
        //$tojson = json_encode($response,JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
        //print_r($tojson);
        
    }



    public function List(Request $request){
        $parameters = [];
        $query = "EXEC dbo.assujettissement_list ";

        if(isset($request['fk_repertoire'])){
            $parameters[] = $request['fk_repertoire'];
            $query .= "?,";
        }

        if(isset($request['fk_natureImpots'])){
            $parameters[] = $request['fk_natureImpots'];
            $query .= "?,";
        }

        $query .= "*,";
        $query = str_replace(",*,","",$query);
        $query = str_replace("*,","",$query);

        $values = DB::select( $query, $parameters);

        
/*
        $response = array();
        $post   = new dgi_assujettissements;
        $values = $post;

        if(isset($fk_natureImpots)){
            $values = $values->where("fk_natureImpots","=",$fk_natureImpots);
        }

        if(isset($impots)){
            $values = $values->where("impots","=",$impots);
        }

        $values = $values->get();

        $values = json_decode(json_encode($values));
*/
        //$all_nifs   = array();
        //$all_impots = array();

        //print_r($values);

        $remote_server = new remote_server;
        $token = $remote_server->RequestNewToken();

        // $remote_server = new remote_server;
        // $nif = 19;
        // $endPoint = "https://api-contribuable-dev.apps.kubedev.hologram.cd/api/contribuable/recherche/nif,";
        // $contribuable = $remote_server->GetData($endPoint.$nif,$token);
        // print_r($contribuable);
        

        /*if(is_array($values)){
            foreach($values as $val){
                $nif = $val->fk_repertoire;

                //$all_nifs = base64_encode(json_encode($all_nifs));

                $remote_server = new remote_server;
                //$endPoint = "https://api-contribuable-dev.apps.kubedev.hologram.cd/api/contribuable/recherche/nif,";
                //$contribuable = $remote_server->GetData($endPoint.$nif,$token);
                //print_r($contribuable);

                //$all_repertoires = json_decode($all_repertoires);

                //$val->fk_repertoire;

                //unset($all_nifs);
    
                /*
                if(!in_array($nif,$all_nifs)){
                    $all_nifs[] = $nif;
                }

                $impot = $val->fk_impots;
                if(!in_array($impot,$all_impots)){
                    $all_impots[] = $impot;
                }
                
            }

            
            // $all_nifs = base64_encode(json_encode($all_nifs));
            // $remote_server = new remote_server;
            // $all_repertoires = $remote_server->GetData("http://localhost/dgi_ms_gestion_contribuable/public/api/for_nifs_v2/".$all_nifs);
            // $all_repertoires = json_decode($all_repertoires);
            // unset($all_nifs);



                        
            // $all_impots = base64_encode(json_encode($all_impots));
            // $remote_server = new remote_server;
            // $all_impots_details = $remote_server->GetData("http://localhost/dgi_ms_gestion_impots/public/api/actes_for_ids_v2/".$all_impots); 
            // $all_impots_details = json_decode($all_impots_details);
            // unset($all_impots);
            // unset($remote_server);


            /*
            foreach($values as $val){
                $nif = $val->fk_repertoire;
                //$impot = $val->fk_impots;

                if(is_array($all_repertoires)){
                    $val->fk_repertoire     = $all_repertoires->$nif;
                }

                //if(is_array($all_impots_details)){
                //    $val->fk_impots         = $all_impots_details->$impot;
                //}
                //unset($val->fk_repertoire);
                //unset($val->impots);
            }
            
            
        } */
        

        $tmp            = new \stdClass();
        $tmp->content   = $values;
        $tmp->info      = NULL;
        $values         = $tmp;

        $values = json_encode($values,JSON_UNESCAPED_UNICODE);
        return response($values, 200)->header('Content-Type', 'text/JSON');       
    }


    public function Update($id, request $request){

        $obj = dgi_assujettissements ::find($id);
        if($obj){   

            $validator = Validator::make($request->all(), [
                //'fk_repertoire'     => 'required|min:5',
                //'dateDebut'         => 'required|date',
                //'fk_natureImpots'   => 'required',
                //'impots'            => 'required',
            ]);
    
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
                
            $obj->fk_repertoire     = $request['fk_repertoire'];
            $obj->dateDebut         = $request['dateDebut'];
            $obj->fk_natureImpots   = $request['fk_natureImpots'];
            $obj->fk_impots         = $request['fk_impots'];
    
        
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
        $obj    = new dgi_assujettissements;      
        $values = $obj->find($id);
        
    
        $tmp            = new \stdClass();
        $tmp->content   = $values;
        $tmp->info      = NULL;
        $values         = $tmp;
        
        $values = json_encode($values,JSON_UNESCAPED_UNICODE);
        return response($values, 200)->header('Content-Type', 'text/JSON');
    }





    public function Terminate($id, request $request){

        $obj = dgi_assujettissements ::find($id);
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
