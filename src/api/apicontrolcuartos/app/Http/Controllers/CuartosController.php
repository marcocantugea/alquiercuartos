<?php

namespace App\Http\Controllers;

use Database\Seeders\addUlimitedCuartosAlquiler;
use Database\Seeders\RestoreCuartosBasicos;
use DateTime;
use Faker\Core\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class CuartosController extends Controller
{
    public function getAllCuartos(){
        $infoCuartos= DB::table('cuartos')
                            ->join('cuartoestatus', 'cuartoestatus.id', '=', 'cuartos.estatusId')
                            ->select('cuartos.publicId','cuartos.codigo','cuartoestatus.estatus','cuartos.created_at','cuartos.descripcion')
                            ->whereNull('cuartos.fecha_eliminado')
                            ->whereNull('cuartoestatus.fecha_eliminado')
                            ->get();

        return new Response($this->stdResponse(data:$infoCuartos));
    }

    public function addCuarto(Request $request){

        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->codigo) && !isset($jsonParsed->estatusId)){
            return new Response($this->stdResponse(false,true,'codigo o estatus id invalido'),400);
        }

        $descripcion= $jsonParsed->descripcion ?? null;

        DB::table('cuartos')->insert([
            ['publicId'=>uniqid(),'codigo'=>$jsonParsed->codigo,'descripcion'=>$descripcion,'estatusId'=>$jsonParsed->estatusId,'created_at'=>new DateTime()]
        ]);

        return new Response($this->stdResponse());
    }

    public function getCuarto($publicId){

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $infoCuarto= DB::table('cuartos')
        ->join('cuartoestatus', 'cuartoestatus.id', '=', 'cuartos.estatusId')
        ->select('cuartos.publicId','cuartos.codigo','cuartoestatus.estatus','cuartos.created_at','cuartos.descripcion')
        ->whereNull('cuartos.fecha_eliminado')
        ->whereNull('cuartoestatus.fecha_eliminado')
        ->where('cuartos.publicId','=',$publicId)
        ->get();

        return new Response($this->stdResponse(data:$infoCuarto));
    }

    public function updateCuarto(Request $request,$publicId){

        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->codigo) && !isset($jsonParsed->estatusId)){
            return new Response($this->stdResponse(false,true,'codigo o estatus id invalido'),400);
        }

        $descripcion= $jsonParsed->descripcion ?? null;

        $item=[
            'codigo'=>$jsonParsed->codigo,
            'estatusId'=>$jsonParsed->estatusId,
            'updated_at'=> new DateTime()
        ];
        
        if(!empty($descripcion)) $item+=['descripcion'=>$descripcion];

        DB::table('cuartos')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());

    }

    public function deleteCuarto($publicId){
        
        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $item=[
            'fecha_eliminado'=>new DateTime()
        ];

        DB::table('cuartos')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());
    }

    public function activaCuarto($publicId){
        
        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);
        
        $item=[
            'estatusId'=>1,
            'updated_at'=> new DateTime()
        ];

        DB::table('cuartos')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());
    }

    public function desactivarCuarto($publicId){
        
        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);
        
        $item=[
            'estatusId'=>3,
            'updated_at'=> new DateTime()
        ];

        DB::table('cuartos')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());
    }

    public function suspenderCuarto($publicId){
        
        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);
        
        $item=[
            'estatusId'=>2,
            'updated_at'=> new DateTime()
        ];

        DB::table('cuartos')->where('publicId',$publicId)->update($item);

        return new Response($this->stdResponse());
    }

    public function addCuartosSinLimite(){
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);
        $seeder= new addUlimitedCuartosAlquiler();
        try {
            $seeder->run();
        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"exception found"),500);
        }

        return new Response($this->stdResponse());
    }

    public function restoreCuartosBasicos(){
        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);
        $seeder= new RestoreCuartosBasicos();
        try {
            $seeder->run();
        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"exception found"),500);
        }
        
        return new Response($this->stdResponse());
    }
}
