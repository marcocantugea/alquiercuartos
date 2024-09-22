<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class ConfiguracionController extends Controller
{
    
    public function addConfiguracion(Request $request){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->variable) && !isset($jsonParsed->valor)){
            return new Response($this->stdResponse(false,true,'variable o valor invalido'),400);
        }

        $id= DB::table('configuraciones')->insertGetId(
            ['publicId'=>uniqid(),'variable'=>$jsonParsed->variable,'valor'=>$jsonParsed->valor,'created_at'=>new DateTime()]
        );

        $configuracion= DB::table('configuraciones')->where('id',$id)->select('publicId','variable','valor','created_at')->first();


        return new Response($this->stdResponse(data:$configuracion));

    }

    public function getConfiguracion($publicId){
        if(empty($publicId)) return new Response($this->stdResponse(false,true,'configuracion invalida'),400);
        $configuracion=DB::table('configuraciones')->where('publicId',$publicId)->select('publicId','variable','valor','created_at','updated_at')->first();
        return new Response($this->stdResponse(data:$configuracion));

    }

    public function getConfiguraciones(){
        $configuraciones=DB::table('configuraciones')->whereNull('fecha_eliminado')->select('publicId','variable','valor','created_at','updated_at')->get();
        return new Response($this->stdResponse(data:$configuraciones));

    }

    public function updateConfiguracion(Request $request, $publicId){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'configuracion invalida'),400);
        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->variable) && !isset($jsonParsed->valor)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $idConfiguracion= DB::table('configuraciones')->where('publicId',$publicId)->whereNull('fecha_eliminado')->select('id')->first();
        if(!isset($idConfiguracion->id)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        DB::table('configuraciones')->where('id',$idConfiguracion->id)->update([
            'variable'=>$jsonParsed->variable,
            'valor'=>$jsonParsed->valor,
            'updated_at'=>new DateTime()
        ]);

        return new Response($this->stdResponse());
    }

    public function deleteConfiguracion($publicId){

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'configuracion invalida'),400);
        $idConfiguracion= DB::table('configuraciones')->where('publicId',$publicId)->whereNull('fecha_eliminado')->select('id')->first();
        if(!isset($idConfiguracion->id)) return new Response($this->stdResponse(false,true,'id invalido'),400);

        DB::table('configuraciones')->where('id',$idConfiguracion->id)->update([
            'fecha_eliminado'=>new DateTime()
        ]);

        return new Response($this->stdResponse());
    }

}
