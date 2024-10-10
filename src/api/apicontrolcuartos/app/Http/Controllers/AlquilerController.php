<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class AlquilerController extends Controller
{
    public function startAlquiler(Request $request){

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->cuartoId) && !isset($jsonParsed->descripcion) && !isset($jsonParsed->fechaEntrada)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $cuartoInfo= DB::table('cuartos')->where('publicId',$jsonParsed->cuartoId)->first();
        if(empty($cuartoInfo)) return new Response($this->stdResponse(false,true,'cuarto invalido'),400);

        if($this->CuartoAlquilado($cuartoInfo->id)) return new Response($this->stdResponse(false,true,'el cuarto esta alquilado, necesita cerrar el alquiler'),400);      

        $folioActual=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','FOLIO_ACTUAL')->whereNull('fecha_eliminado')->first();
        $siguienteFolio=(!isset($folioActual)) ?  1 : intval($folioActual->valor)+1;

        $id=DB::table('cuartosalquiler')->insertGetId(
            [
                'publicId'=>uniqid(),
                'cuartoId'=>$cuartoInfo->id,
                'descripcion_alquiler'=>$jsonParsed->descripcion,
                'fecha_entrada'=>$jsonParsed->fechaEntrada,
                'created_at'=>new DateTime(),
                'ticket_impreso'=>false,
                'ticket_reimpreso'=>false,
                'folio'=>$siguienteFolio
                ]
        );

        DB::table('cuartosconfiguracion')->where('nombre','FOLIO_ACTUAL')->whereNull('fecha_eliminado')->update([
            'valor'=>$siguienteFolio
        ]);

        $itemAdded= DB::table('cuartosalquiler')
        ->select('publicId','descripcion_alquiler','fecha_entrada','created_at','folio')
        ->where('id',$id)
        ->first();

        $object=[
            'publicId'=>$itemAdded->publicId,
            'cuartoId'=>$cuartoInfo->publicId,
            'cuartoCodigo'=>$cuartoInfo->codigo,
            'fecha_entrada'=>$itemAdded->fecha_entrada,
            'created_at'=>$itemAdded->created_at,
            'folio'=>$itemAdded->folio,
            'descripcion'=>$itemAdded->descripcion_alquiler
        ];

        Log::info("AlquilerController|startAlquiler|inicia alquiler|".json_encode($object));

        return new Response($this->stdResponse(data:$object));
    }

    public function previewAlquiler(Request $request, $publicId){

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->fechaSalida)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $alquiler= DB::table('cuartosalquiler')
                    ->join('cuartos','cuartos.Id','=','cuartosalquiler.cuartoId')
                    ->where('cuartosalquiler.publicId',$publicId)
                    ->select(
                        'cuartosalquiler.publicId',
                        'fecha_entrada',
                        'fecha_salida',
                        'folio',
                        'cuartos.publicId as cuartoId'
                        )
                    ->first();

        //validamos que esta aun abierto el alquiler
        if(!empty($alquiler->fecha_salida)) return new Response($this->stdResponse(false,true,'el alquiler ya esta terminado'),400);

        //sacamos los minutos 
        $Inicio= new DateTime($alquiler->fecha_entrada);
        $Final=new DateTime($jsonParsed->fechaSalida);

        if($Final<=$Inicio) return new Response($this->stdResponse(false,true,'fecha final invalida'),500);

        $difference = date_diff($Inicio, $Final); 
        $minutes = $difference->days * 24 * 60;
        $minutes += $difference->h * 60;
        $minutes += $difference->i;
        $minutes =$minutes+ ($difference->s/100);

        //actualizamos revistro en base de datos

        $item=[
            'fecha_salida'=>$Final->format('Y-m-d H:i:s'),
            'total_minutos'=>$minutes,
            'total_pagado'=>$this->GetTotalAPagar($minutes),
            'updated_at'=>new DateTime()
        ];

        $costoAlquiler= DB::table('cuartosconfiguracion')->where('nombre','Precio_Alquiler_Cuarto')->first()->valor;

        $cobroExtra=($item['total_pagado']>floatval($costoAlquiler)) ? $item['total_pagado']-$costoAlquiler : 0;

        $totalPagado=$costoAlquiler+$cobroExtra;

        $object=[
            'fecha_inicio'=>$alquiler->fecha_entrada,
            'fecha_salida'=>$item['fecha_salida'],
            'total_minutos'=>$item['total_minutos'],
            'total_horas'=>$this->GetTotalHoras($minutes),
            'total_pagado'=>number_format(floatval($totalPagado),2),
            'cobro_extra'=>number_format($cobroExtra,2,".",","),
            'updated_at'=>$item['updated_at'],
            'cuartoId'=>$alquiler->cuartoId,
            'folio'=>$alquiler->folio,
            'publicId'=>$alquiler->publicId,
            'costoAlquiler'=>number_format($costoAlquiler,2,".",",")
        ];

        return new Response($this->stdResponse(data:$object));
    }

    public function stopAlquiler(Request $request,$publicId){

        if(empty($publicId)) return new Response($this->stdResponse(false,true,"invalid id"),400);

        $jsonParsed= json_decode($request->getContent());
        if(!isset($jsonParsed->fechaSalida)){
            return new Response($this->stdResponse(false,true,'nombre o valor invalido'),400);
        }

        $alquiler= DB::table('cuartosalquiler')->where('publicId',$publicId)->first();

        //validamos que esta aun abierto el alquiler
        if(!empty($alquiler->fecha_salida)) return new Response($this->stdResponse(false,true,'el alquiler ya esta terminado'),400);

        //sacamos los minutos 
        $Inicio= new DateTime($alquiler->fecha_entrada);
        $Final=new DateTime($jsonParsed->fechaSalida);

        if($Final<=$Inicio) return new Response($this->stdResponse(false,true,'fecha final invalida'),500);

        $difference = date_diff($Inicio, $Final); 
        $minutes = $difference->days * 24 * 60;
        $minutes += $difference->h * 60;
        $minutes += $difference->i;
        $minutes =$minutes+ ($difference->s/100);

        //actualizamos revistro en base de datos

        $item=[
            'fecha_salida'=>$Final,
            'total_minutos'=>$minutes,
            'total_pagado'=>$this->GetTotalAPagar($minutes),
            'updated_at'=>new DateTime()
        ];

        DB::table('cuartosalquiler')->where('id',$alquiler->id)->update($item);
        $costoAlquiler= DB::table('cuartosconfiguracion')->where('nombre','Precio_Alquiler_Cuarto')->first()->valor;

        $cobroExtra=($item['total_pagado']>floatval($costoAlquiler)) ? $item['total_pagado']-$costoAlquiler : 0;

        $object=[
            'fecha_inicio'=>$alquiler->fecha_entrada,
            'fecha_salida'=>$item['fecha_salida'],
            'total_minutos'=>$item['total_minutos'],
            'total_pagado'=>$item['total_pagado'],
            'cobro_extra'=>$cobroExtra,
            'updated_at'=>$item['updated_at']
        ];

        Log::info("AlquilerController|stopAlquiler|cierra alquier alquiler|".$publicId."|".json_encode($object));
        return new Response($this->stdResponse(data:$object));
    }

    public function getAlquileresActivos(Request $request){

        $folio=$request->query('folio');

        $items= DB::table('cuartosalquiler')
                ->join('cuartos','cuartos.id','=','cuartosalquiler.cuartoId')
                ->join('cuartoestatus','cuartoestatus.id','=','cuartos.estatusId')
                ->select('cuartosalquiler.publicId',
                            'cuartosalquiler.descripcion_alquiler',
                            'cuartosalquiler.fecha_entrada',
                            'cuartosalquiler.created_at',
                            'cuartosalquiler.folio',
                            'cuartos.publicId as cuartoId',
                            'cuartos.codigo',
                            'cuartos.descripcion as cuartoDescripcion',
                            'cuartoestatus.estatus')
                ->whereNull('fecha_salida')
                ->whereNull('cuartosalquiler.fecha_eliminado')
                ;

        
        if(!empty($folio)) $items= $items->where('folio',$folio);

        $items= $items->get();

        $dtos=[];

        foreach ($items as $item) {

            //calculamos los minutos y segundos transcurridos
            $fechaInicial=new DateTime($item->fecha_entrada);
            $now= new DateTime('now',new DateTimeZone('America/Mazatlan'));

            $difference = date_diff($now,$fechaInicial); 
            $daysdiff = $difference->days * 24 * 60;
            $horas = $difference->h -6;
            $minutos = $difference->i;
            $segundos= $difference->s;

            $minutosTrans= floor(abs($fechaInicial->getTimestamp() - $now->getTimestamp()) / 60);

            $dtos[]=[
                'publicId'=>$item->publicId,
                'folio'=>$item->folio,
                'descripcion'=>$item->descripcion_alquiler,
                'fecha_entrada'=>$item->fecha_entrada,
                'created_at'=>$item->created_at,
                'cuarto'=>[
                    'publicId'=>$item->cuartoId,
                    'codigo' => $item->codigo,
                    'descripcion'=>$item->cuartoDescripcion,
                    'estatus'=>$item->estatus
                ],
                'fecha_salida'=>$now->format('Y-m-d h:i:s'),
                'horasTrans'=>$horas,
                'minutosTrans'=>$minutos,
                'segundosTrans'=>$segundos
            ];
        }
       
        return new Response($this->stdResponse(data:$dtos));
    }

    private function CuartoAlquilado($cuartoId) : bool{
        $alquilado= false;

        $item= DB::table('cuartosalquiler')->where('cuartoId',$cuartoId)->whereNull('fecha_salida')->whereNull('fecha_eliminado')->whereNull('total_pagado')->get();

        if(count($item) > 0) $alquilado=true;

        return $alquilado;
    }

    public function GetTotalAPagar($minutos) : float {

        //obtenemos la configuracion de los cuartos

        $costoAlquiler= DB::table('cuartosconfiguracion')->where('nombre','Precio_Alquiler_Cuarto')->first()->valor;
        $minutosRenta= DB::table('cuartosconfiguracion')->where('nombre','Tiempo_Renta_Cuarto_Min')->first()->valor;
        $tolerancia= DB::table('cuartosconfiguracion')->where('nombre','Tiempo_Tolerancia_Min')->first()->valor;

        $costoTotal=floatval($costoAlquiler);

        $divMinutos=$minutos/intval($minutosRenta);

        $costoTotal+=floor($divMinutos)*intval($costoAlquiler);

        //revisamos si tenemos tolerancia
        $toleranciaMinutos= fmod(floatval($divMinutos),1);
        $toleranciaDiv=(floatval($tolerancia)/floatval($minutosRenta))-.001;

        if($toleranciaMinutos>=0 && $toleranciaMinutos<$toleranciaDiv) $costoTotal-=$costoAlquiler;
        if($costoTotal==0)  $costoTotal=floatval($costoAlquiler);
        Log::info("AlquilerController|GetTotalAPagar|Calculo Total A Pagar|minutos ".$minutos."|".$costoTotal);
        return $costoTotal;
    }

    private function GetTotalHoras($minutos){

        $horas= floor($minutos/60);
        $minutosSel=($minutos/60);
        
        if($minutosSel<1){
            $minutosSel=$minutosSel*60;
        }else{
            $minutosSel=($minutosSel-$horas)*60;
        }
         
        return $horas." hrs ".number_format($minutosSel,0)." min";

    }

    public function searchAlquilerByTokenFolio(Request $request){
        $folio=$request->query('folio');
        //if(strlen($folio)<=4) return new Response('invalid folio',400);

        $seconds=substr($folio,0,2);
        $minutes=substr($folio,2,2);
        //$folioTicket=substr($folio,4);

        $items= DB::table('cuartosalquiler')
        ->join('cuartos','cuartos.id','=','cuartosalquiler.cuartoId')
        ->join('cuartoestatus','cuartoestatus.id','=','cuartos.estatusId')
        ->select('cuartosalquiler.publicId',
                    'cuartosalquiler.descripcion_alquiler',
                    'cuartosalquiler.fecha_entrada',
                    'cuartosalquiler.created_at',
                    'cuartosalquiler.folio',
                    'cuartos.publicId as cuartoId',
                    'cuartos.codigo',
                    'cuartos.descripcion as cuartoDescripcion',
                    'cuartoestatus.estatus',
                    )
        ->whereNull('fecha_salida')
        ->whereNull('cuartosalquiler.fecha_eliminado')
        ->where('cuartosalquiler.folio',$folio)
        // ->whereRaw('MINUTE(cuartosalquiler.fecha_entrada)='.$minutes)
        // ->whereRaw('SECOND(cuartosalquiler.fecha_entrada)='.$seconds)
        ;

        $item= $items->first();

        $dtos=[];

        //foreach ($items as $item) {

            //calculamos los minutos y segundos transcurridos
            $fechaInicial=new DateTime($item->fecha_entrada);
            $now= new DateTime('now',new DateTimeZone('America/Mazatlan'));

            $difference = date_diff($now,$fechaInicial); 
            $daysdiff = $difference->days * 24 * 60;
            $horas = $difference->h -6;
            $minutos = $difference->i;
            $segundos= $difference->s;

            $minutosTrans= floor(abs($fechaInicial->getTimestamp() - $now->getTimestamp()) / 60);

            $dtos[]=[
                'publicId'=>$item->publicId,
                'folio'=>$item->folio,
                'descripcion'=>$item->descripcion_alquiler,
                'fecha_entrada'=>$item->fecha_entrada,
                'created_at'=>$item->created_at,
                'cuarto'=>[
                    'publicId'=>$item->cuartoId,
                    'codigo' => $item->codigo,
                    'descripcion'=>$item->cuartoDescripcion,
                    'estatus'=>$item->estatus
                ],
                'fecha_salida'=>$now->format('Y-m-d h:i:s'),
                'horasTrans'=>$horas,
                'minutosTrans'=>$minutos,
                'segundosTrans'=>$segundos
            ];
        //}
       
        return new Response($this->stdResponse(data:$dtos));
    }
}
