<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class CuartosConfiguracionController extends Controller
{
    public function getAll(){

        $items= DB::table('cuartosconfiguracion')
                    ->select('publicId','nombre','valor')
                    ->whereNull('fecha_eliminado')
                    ->get();

        return new Response($this->stdResponse(data:$items));
    } 

    public function get($publicId){

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $item= DB::table('cuartosconfiguracion')
        ->select('publicId','nombre','valor')
        ->whereNull('fecha_eliminado')
        ->where('publicId',$publicId)
        ->get();

        return new Response($this->stdResponse(data:$item));
    }

    public function getByName($nombre){
        if(empty($nombre)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $item= DB::table('cuartosconfiguracion')
        ->select('publicId','nombre','valor')
        ->whereNull('fecha_eliminado')
        ->where('nombre',$nombre)
        ->get();

        return new Response($this->stdResponse(data:$item));
    }

    public function addConfiguracion(Request $request){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->nombre) && !isset($jsonParsed->valor)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        
        DB::table('cuartosconfiguracion')->insert([
            ['publicId'=>uniqid(),'created_at'=>new DateTime(),'nombre'=>$jsonParsed->nombre,'valor'=>$jsonParsed->valor]
        ]);

        return new Response($this->stdResponse());
    }

    public function updateConfiguration(Request $request, $publicId){
        
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);
        
        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->nombre) && !isset($jsonParsed->valor)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $item=[
            'nombre'=>$jsonParsed->nombre,
            'valor'=>$jsonParsed->valor,
            'updated_at'=>new DateTime()
        ];

        DB::table('cuartosconfiguracion')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());

    }

    public function deleteConfiguracion($publicId){
        
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);
        $item=[
            'fecha_eliminado'=>new DateTime()
        ];

        DB::table('cuartosconfiguracion')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());
    }
}
