<?php

namespace App\Http\Controllers\DOC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function Home(){

        echo "la";
    }

    public function View($id){

        return  view('DOC/API/view_'.$id);;
    }

}
