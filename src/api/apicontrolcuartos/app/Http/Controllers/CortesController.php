<?php 

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CortesController extends Controller
{
    
    public function GetCorteResumen(Request $request){

        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $fechaInicio=$request->query('fechaInicio');
        $fechaFechaFin=$request->query('fechaFin');
        
        if(empty($fechaInicio) || empty($fechaFechaFin)) return  new Response($this->stdResponse(false,true,"fechas invalidas"),400);

        try {
            $fechaInicioObj=new DateTime($fechaInicio);
            $fechaFechaFinObj=new DateTime($fechaFechaFin);
    } catch (\Throwable $th) {
            return  new Response($this->stdResponse(false,true,"fechas invalidas"),400);
        }
        
        $infoTicket=$this->GetCorteCajaResumenData($fechaInicioObj->format('d'),$fechaInicioObj->format('m'),$fechaInicioObj->format('Y'),$fechaFechaFinObj->format('d'),$fechaFechaFinObj->format('m'),$fechaFechaFinObj->format('Y'));

        if(empty($infoTicket)) return  new Response($this->stdResponse(false,true,"no data found"),404);

        return new Response($this->stdResponse(data:$infoTicket));

    }

    public function GetCorteDetalle(Request $request){
        
        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $fechaInicio=$request->query('fechaInicio');
        $fechaFechaFin=$request->query('fechaFin');
        
        if(empty($fechaInicio) || empty($fechaFechaFin)) return  new Response($this->stdResponse(false,true,"fechas invalidas"),400);

        try {
            $fechaInicioObj=new DateTime($fechaInicio);
            $fechaFechaFinObj=new DateTime($fechaFechaFin);
        } catch (\Throwable $th) {
            return  new Response($this->stdResponse(false,true,"fechas invalidas"),400);
        }
        
        $infoTicket=$this->GetCorteDetalleData($fechaInicioObj->format('d'),$fechaInicioObj->format('m'),$fechaInicioObj->format('Y'),$fechaFechaFinObj->format('d'),$fechaFechaFinObj->format('m'),$fechaFechaFinObj->format('Y'),true);

        if(empty($infoTicket)) return  new Response($this->stdResponse(false,true,"no data found"),404);

        return new Response($this->stdResponse(data:$infoTicket));

    }

    private function GetCorteDetalleData($diaInicio, $mesInicio, $anioInicio, $diaFin,$mesFin,$anioFin){
        return $this->GetCorteCajaResumenData($diaInicio, $mesInicio, $anioInicio, $diaFin,$mesFin,$anioFin,true);
    }

    private function GetCorteCajaResumenData($diaInicio, $mesInicio, $anioInicio, $diaFin,$mesFin,$anioFin,bool $detalle=false){
        
        $fechaInicio= new DateTime($anioInicio."-".$mesInicio."-".$diaInicio." 00:00:00");
        $fechaFin= new DateTime($anioFin."-".$mesFin."-".$diaFin." 23:00:00");

        $ultimoFolio=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','FOLIO_ACTUAL')->first();
        $precioAlquiler=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','Precio_Alquiler_Cuarto')->first();

        $alquileres=$this->CorteCajaData($fechaInicio,$fechaFin);

        $totalPagado=$alquileres->reduce(function($carry,$item){
            if(!empty($item->cancelacionId)) return $carry;
            $carry+=floatval($item->total_pagado);
            return $carry;
        });

        $totalCanceladoParcial=$alquileres->reduce(function($carry,$item){
            if(!empty($item->cancelacionId) && !boolval($item->cancelacionAprobada)) $carry+=floatval($item->total_pagado);
            return $carry;
        });

        $totalCancelado=$alquileres->reduce(function($carry,$item){
            if(boolval($item->cancelacionAprobada)) $carry+=floatval($item->total_pagado);
            return $carry;
        });

        if ($alquileres->count()<=0) return null;

        $detalleRecords=null;
        if($detalle){
            $detalleRecords=$alquileres;
        }

        $obj=[
            'ultimoFolio'=>(!isset($ultimoFolio->valor)) ? 1 : $ultimoFolio->valor,
            'periodoInicio'=>($alquileres->count()>0) ? $alquileres->first()->fecha_entrada : null,
            'periodoFin'=>($alquileres->count()>0) ? $alquileres->last()->fecha_salida : null,
            'ticketsCobrados'=>intval($alquileres->whereNull('cancelacionId')->count()),
            'ticketCanceladoParcial'=>intval($alquileres->whereNotNull('cancelacionId')->where('cancelacionAprobada',0)->count()),
            'ticketCanceladoAutorizados'=>intval($alquileres->where('cancelacionAprobada',1)->count()),
            'Extras'=>intval($alquileres->where('total_pagado','>',$precioAlquiler->valor)->whereNull('cancelacionId')->count()),
            'MontoTotal'=>(empty($totalPagado)) ? 0 : number_format($totalPagado,2),
            'MontoCanceladoParcial'=>(empty($totalCanceladoParcial)) ? 0 : number_format($totalCanceladoParcial,2),
            'MontoCancelado'=>(empty($totalCancelado))? 0 : number_format($totalCancelado,2),
            'detalle'=>[
                'length'=>count($alquileres),
                'data'=>$detalleRecords
            ],
            
        ];

        return $obj;
    }

    private function CorteCajaData($fechaInicio,$fechaFin): Collection{
        return DB::table('cuartosalquiler')
        ->join('cuartos','cuartos.id','=','cuartosalquiler.cuartoId')
        ->join('cuartoestatus','cuartoestatus.id','=','cuartos.estatusId')
        ->leftJoin('cancelaciones','cancelaciones.cuartoAlquilerId','=','cuartosalquiler.id')
        ->select('cuartosalquiler.publicId',
                    'cuartosalquiler.descripcion_alquiler',
                    'cuartosalquiler.fecha_entrada',
                    'cuartosalquiler.created_at',
                    'cuartosalquiler.folio',
                    'cuartosalquiler.fecha_salida',
                    'cuartosalquiler.total_minutos',
                    'cuartosalquiler.total_pagado',
                    'cuartosalquiler.updated_at',
                    'cuartos.publicId as cuartoId',
                    'cuartos.codigo',
                    'cuartos.descripcion as cuartoDescripcion',
                    'cuartoestatus.estatus',
                    'cancelaciones.id as cancelacionId',
                    'cancelaciones.fecha_cancelacion',
                    'cancelaciones.motivo_cancelacion',
                    'cancelaciones.aprobado as cancelacionAprobada',
                    'cancelaciones.fechaAprobacion',
                    'cancelaciones.nota_aprobacion'
                    )
        ->where('cuartosalquiler.fecha_entrada','>=',$fechaInicio)
        ->where('cuartosalquiler.fecha_salida','<=',$fechaFin)
        ->whereNull('cuartosalquiler.fecha_eliminado')
        ->get();

    }
}
