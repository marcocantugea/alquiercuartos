<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class CancelacionesController extends Controller
{
    
    public function addCancelacionParcial(Request $request,$alquilerId){

        if(empty($alquilerId)) return new Response($this->stdResponse(false,true,'alquiler invalido'),400);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->motivo) && !isset($jsonParsed->usuarioId)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $alquiler= DB::table('cuartosalquiler')->where('publicId',$alquilerId)->whereNull('fecha_eliminado')->first();
        $usuario= DB::table('usuarios')->where('publicId',$jsonParsed->usuarioId)->whereNull('fecha_eliminado')->first();

        if(!isset($alquiler->id)) return new Response($this->stdResponse(false,true,"id alquiler invalido"),400);
        if(!isset($usuario->id)) return new Response($this->stdResponse(false,true,"id usuario invalido"),400);
        if(!$this->validaAlquiler($alquiler)) return new Response($this->stdResponse(false,true,"alquiler invalido"),400);

        $id= DB::table('cancelaciones')->insertGetId(
            ['publicId'=>uniqid(),'cuartoAlquilerId'=>$alquiler->id,'usuarioId'=>$usuario->id,'fecha_cancelacion'=>new DateTime(),'motivo_cancelacion'=>$jsonParsed->motivo,'created_at'=>new DateTime()]
        );

        $cancelacionParcial= DB::table('cancelaciones')
                                ->join('cuartosalquiler','cuartosalquiler.id','=','cancelaciones.cuartoAlquilerId')
                                ->join('cuartos','cuartos.id','=','cuartosalquiler.cuartoId')
                                ->join('usuarios','usuarios.id','=','cancelaciones.usuarioId')
                                ->select(
                                    'cancelaciones.publicId',
                                    'cancelaciones.fecha_cancelacion',
                                    'cancelaciones.motivo_cancelacion',
                                    'cancelaciones.created_at',
                                    'cuartosalquiler.publicId as alquilerPublicId',
                                    'cuartos.publicId as cuartoId',
                                    'cuartos.codigo',
                                    'cuartos.descripcion',
                                    'cuartosalquiler.descripcion_alquiler',
                                    'cuartosalquiler.fecha_entrada',
                                    'cuartosalquiler.fecha_salida',
                                    'cuartosalquiler.total_minutos',
                                    'cuartosalquiler.total_pagado',
                                    'cuartosalquiler.created_at as cuartosalquilerCreatedAt',
                                    )
                                    ->where('cancelaciones.id',$id)
                                    ->first();

        $obj=[
            'publicId'=>$cancelacionParcial->publicId,
            'fechaCancelacion'=>$cancelacionParcial->fecha_cancelacion,
            'motivo'=>$cancelacionParcial->motivo_cancelacion,
            'created_at'=>$cancelacionParcial->created_at,
            'alquiler'=>[
                'publicId'=>$cancelacionParcial->alquilerPublicId,
                'descripcion'=>$cancelacionParcial->descripcion_alquiler,
                'fechaEntrada'=>$cancelacionParcial->fecha_entrada,
                'fechaSalida'=>$cancelacionParcial->fecha_salida,
                'totalMinutos'=>$cancelacionParcial->total_minutos,
                'total'=>$cancelacionParcial->total_pagado,
                'created_at'=>$cancelacionParcial->cuartosalquilerCreatedAt,
                'cuarto'=>[
                    'publicId'=>$cancelacionParcial->cuartoId,
                    'codigo'=>$cancelacionParcial->codigo,
                    'descripcion'=>$cancelacionParcial->descripcion
                ]
            ],
        ];

        return new Response($this->stdResponse(data:$obj));
    }

    public function aprobarCancelacion(Request $request,$publicId){

        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        if(empty($publicId)) return new Response($this->stdResponse(false,true,'alquiler invalido'),400);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->usuarioId) || !isset($jsonParsed->nota)){
            return new Response($this->stdResponse(false,true,'usuario invalido'),400);
        }

        $usuarioRol= DB::table('usuarios')
                        ->join('roles','roles.id','=','usuarios.roleId')
                        ->where('usuarios.publicId',$jsonParsed->usuarioId)
                        ->where('usuarios.activo',true)
                        ->whereNull('usuarios.fecha_eliminado')
                        ->select('roles.rol','usuarios.id')
                        ->first();

        if(!isset($usuarioRol->rol)) return new Response($this->stdResponse(false,true,'usuario invalido'),400);

        $roles=['Administrador','Supervisor'];

        if(!in_array($usuarioRol->rol,$roles)) return new Response($this->stdResponse(false,true,'usuario no tiene privilegios para aprovar cancelaciones'),500);

        $cancelacion=DB::table('cancelaciones')->where(['publicId'=>$publicId, 'aprobado'=>false ])->whereNull('fecha_eliminado')->first();

        if(empty($cancelacion)) return new Response($this->stdResponse(false,true,'cancelacion invalida'),500);

        DB::table('cancelaciones')->where('id',$cancelacion->id)->update([
            'aprobado'=>true,
            'aprobadoPorId'=>$usuarioRol->id,
            'fechaAprobacion'=>new DateTime(),
            'nota_aprobacion'=>$jsonParsed->nota,
            'updated_at'=>new DateTime()
        ]);

        return new Response($this->stdResponse());
    }

    private function validaAlquiler($alquiler):bool{

        $valido=true;

        if(empty($alquiler->fecha_salida)) $valido=false;
        if($alquiler->total_pagado<=0) $valido=false;
        if($alquiler->total_minutos<=0) $valido=false;
        if(!empty($alquiler->fecha_eliminado)) $valido=false;
        

        return $valido;

    }
}
