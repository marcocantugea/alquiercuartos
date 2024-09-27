<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;

final class VersionController extends Controller{
    
    public function GetVersion(){
        $fileExist= getcwd()."\\version.json";
        if(file_exists($fileExist)){
            $file = file_get_contents($fileExist, true);
            $jsonContent=json_decode($file);
            return (new Response(json_encode($jsonContent)))->header('Content-Type', 'application/json');
        }
       return new Response("not found");
    }

}
