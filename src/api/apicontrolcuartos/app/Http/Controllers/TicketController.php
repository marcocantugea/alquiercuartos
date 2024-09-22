<?php

namespace App\Http\Controllers;

use App\Services\PrinterService;
use App\Services\PrintLayoutService;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


final class TicketController extends Controller
{
    /**@var PrinterService  */
    private PrinterService $printerService;
    /**@var PrintLayoutService  */
    private PrintLayoutService $printerLayoutService; 
    
    public function __construct(PrinterService $printerService,PrintLayoutService $printLayoutService) {
        $this->printerService = $printerService;
        $this->printerLayoutService= $printLayoutService;
    }

    public function PrintTestTicket(){
        $template=DB::table('configuraciones')->select('valor')->where('variable','TICKET_PRUEBA')->whereNull('fecha_eliminado')->first();
        if(!isset($template->valor)) return new Response($this->stdResponse(false,true,"plantilla no encotrada"),400);

        $this->setupLayout($template->valor,['fechaDatos'=>'variable actualizada dinamicamente']);
        try {
            $response=$this->printerService->setLayout($this->printerLayoutService)->prepare()->print();

            if($response===false) return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$response),500);

        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$th->getMessage()),500);
        }
        
        return new Response($this->stdResponse());
    }

    public function PrintTicketInicioAlquiler($alquierId){
        $template=DB::table('configuraciones')->select('valor')->where('variable','TICKET_INICIO_ALQUILER')->whereNull('fecha_eliminado')->first();
        $minutosRentaDB=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','Tiempo_Renta_Cuarto_Min')->whereNull('fecha_eliminado')->first();
        if(!isset($template->valor)) return new Response($this->stdResponse(false,true,"plantilla no encotrada"),400);
        $minutosRenta=(!isset($minutosRentaDB->valor)) ? "20" : $minutosRentaDB->valor;
        $datosEmpresa=$this->getDatosEmpresa();

        $alquiler= DB::table('cuartosalquiler')
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
        ->where('cuartosalquiler.publicId',$alquierId)
        ->first();
        
        $infoTicket=[
            'nombreEmpresa'=>$datosEmpresa['nombreEmpresa'],
            'direccionCalle'=>$datosEmpresa['direccionCalle'],
            'direccionNumero'=>$datosEmpresa['direccionNumero'],
            'direccionEstatoCiudad'=>$datosEmpresa['direccionEstatoCiudad'],
            'direccionCP'=>$datosEmpresa['direccionCP'],
            'telefono'=>$datosEmpresa['telefono'],
            'fecha_entrada'=>$alquiler->fecha_entrada,
            'descripcion'=>$alquiler->descripcion_alquiler,
            'publicId'=>strtoupper($alquiler->publicId),
            'folio'=>$alquiler->folio,
            'tiempo'=>$minutosRenta,
            'codigoBarras'=>$alquiler->folio
        ];

        $this->setupLayout($template->valor,$infoTicket);
        try {
            $response=$this->printerService->setLayout($this->printerLayoutService)->prepare()->print();

            if($response===false) return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$response),500);

        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$th->getMessage()),500);
        }
        
        return new Response($this->stdResponse());
    }

    public function PrintTicketCobro($alquierId){
        
        $template=DB::table('configuraciones')->select('valor')->where('variable','TICKET_FIN_ALQUILER')->whereNull('fecha_eliminado')->first();
        if(!isset($template->valor)) return new Response($this->stdResponse(false,true,"plantilla no encotrada"),400);

        $datosEmpresa=$this->getDatosEmpresa();

        $alquiler= $items= DB::table('cuartosalquiler')
        ->join('cuartos','cuartos.id','=','cuartosalquiler.cuartoId')
        ->join('cuartoestatus','cuartoestatus.id','=','cuartos.estatusId')
        ->select('cuartosalquiler.publicId',
                    'cuartosalquiler.descripcion_alquiler',
                    'cuartosalquiler.fecha_entrada',
                    'cuartosalquiler.created_at',
                    'cuartosalquiler.folio',
                    'cuartosalquiler.fecha_salida',
                    'cuartosalquiler.total_minutos',
                    'cuartosalquiler.total_pagado',
                    'cuartosalquiler.updated_at',
                    'cuartosalquiler.ticket_impreso',
                    'cuartos.publicId as cuartoId',
                    'cuartos.codigo',
                    'cuartos.descripcion as cuartoDescripcion',
                    'cuartoestatus.estatus')
        ->where('cuartosalquiler.publicId',$alquierId)
        ->first();
        
        $infoTicket=[
            'nombreEmpresa'=>$datosEmpresa['nombreEmpresa'],
            'direccionCalle'=>$datosEmpresa['direccionCalle'],
            'direccionNumero'=>$datosEmpresa['direccionNumero'],
            'direccionEstatoCiudad'=>$datosEmpresa['direccionEstatoCiudad'],
            'direccionCP'=>$datosEmpresa['direccionCP'],
            'telefono'=>$datosEmpresa['telefono'],
            'fecha_entrada'=>$alquiler->fecha_entrada,
            'descripcion'=>$alquiler->descripcion_alquiler,
            'publicId'=>strtoupper($alquiler->publicId),
            'folio'=>$alquiler->folio,
            'fecha_salida'=>$alquiler->fecha_salida,
            'totalPago'=> number_format(floatval($alquiler->total_pagado), 2),
        ];

        $this->setupLayout($template->valor,$infoTicket);
        try {
            $response=$this->printerService->setLayout($this->printerLayoutService)->prepare()->print();

            if($response===false) return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$response),500);

        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$th->getMessage()),500);
        }
        
        if(!$alquiler->ticket_impreso){
            DB::table('cuartosalquiler')->where('cuartosalquiler.publicId',$alquierId)->update([
                'ticket_impreso'=>true
            ]);
        }else{
            DB::table('cuartosalquiler')->where('cuartosalquiler.publicId',$alquierId)->update([
                'ticket_reimpreso'=>true
            ]);
        }
        Log::info("TicketController|PrintTicketCobro|impresion ticket|alquiler ".$alquierId."|".json_encode($infoTicket));
        return new Response($this->stdResponse());

    }

    public function PrintCorteCajaResumen(Request $request){
        
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
        
        $infoTicket=$this->GetCorteCajaResumen($fechaInicioObj->format('d'),$fechaInicioObj->format('m'),$fechaInicioObj->format('Y'),$fechaFechaFinObj->format('d'),$fechaFechaFinObj->format('m'),$fechaFechaFinObj->format('Y'));

        if(empty($infoTicket)) return  new Response($this->stdResponse(false,true,"no data found"),404);

        //seteamos el ticket
        $template=DB::table('configuraciones')->select('valor')->where('variable','TICKET_CORTECAJA_RESUMEN')->whereNull('fecha_eliminado')->first();
        if(!isset($template->valor)) return new Response($this->stdResponse(false,true,"plantilla no encotrada"),400);

        $this->setupLayout($template->valor,$infoTicket);
        try {
            $response=$this->printerService->setLayout($this->printerLayoutService)->prepare()->print();

            if($response===false) return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$response),500);

        } catch (\Throwable $th) {
            return new Response($this->stdResponse(false,true,"error al imprimir el ticket",$th->getMessage()),500);
        }

        $sendEmail= DB::table('configuraciones')->select('valor')->where('variable','EMAIL_ENVIAR_CORTE')->whereNull('fecha_eliminado')->first();
        $mailToSend= DB::table('configuraciones')->select('valor')->where('variable','EMAIL_TO_ENVIAR_CORTE')->whereNull('fecha_eliminado')->first();
        $mailToSendName=DB::table('configuraciones')->select('valor')->where('variable','EMAIL_TO_ENVIAR_CORTE_NOMBRE')->whereNull('fecha_eliminado')->first();
        $mailCCSend= DB::table('configuraciones')->select('valor')->where('variable','EMAIL_CC_ENVIAR_CORTE')->whereNull('fecha_eliminado')->first();

        if(!empty($sendEmail->valor) && $sendEmail->valor=="1" ){
            Mail::send("mail", $infoTicket, function($message) use (&$infoTicket,&$mailToSend,&$mailToSendName,&$mailCCSend) {
                $message->to($mailToSend->valor, $mailToSendName->valor)
                ->cc($mailCCSend->valor)
                ->cc("info.controlalquilersys@gmail.com")
                ->subject("Corte de Caja del Dia " . $infoTicket['fechaTicket']);
                $message->from("info.controlalquilersys@gmail.com","SistemaControlAlquier");
                });
        }

        Log::info("TicketController|PrintCorteCajaResumen|impresion ticket corte caja|".json_encode($infoTicket));
        return new Response($this->stdResponse(data:$infoTicket));
    }

    private function setupLayout(string $template,array $data){

        $templatePath=DB::table('configuraciones')->select('valor')->where('variable','PRINTER_TEMPLATE_PATH')->whereNull('fecha_eliminado')->first();
        
        if(!isset($templatePath)) throw new Exception("printer templates path not found");
        $templateCompletePath=$templatePath->valor.$template;


        $this->printerLayoutService->setLayout($templateCompletePath)->setData($data)->ExtractContent();
    }

    private function getDatosEmpresa(){

        $nombreEmpresa=DB::table('configuraciones')->select('valor')->where('variable','NOMBRE_EMPRESA')->first();
        $direccionCalle=DB::table('configuraciones')->select('valor')->where('variable','EMPRESA_DIRECCION_CALLE')->first();
        $direccionNumero=DB::table('configuraciones')->select('valor')->where('variable','EMPRESA_DIRECCION_NUMERO')->first();
        $direccionCiudad=DB::table('configuraciones')->select('valor')->where('variable','EMPRESA_DIRECCION_ESTADOCIUDAD')->first();
        $direccionCP=DB::table('configuraciones')->select('valor')->where('variable','EMPRESA_DIRECCION_CODIGOPOSTAL')->first();
        $telefono=DB::table('configuraciones')->select('valor')->where('variable','EMPRESA_TELEFONO')->first();

        $obj=[
            'nombreEmpresa'=>$nombreEmpresa->valor,
            'direccionCalle'=>$direccionCalle->valor,
            'direccionNumero'=>$direccionNumero->valor,
            'direccionEstatoCiudad'=>$direccionCiudad->valor,
            'direccionCP'=>$direccionCP->valor,
            'telefono'=>$telefono->valor
        ];

        return $obj;
    }

    private function GetCorteCajaResumen($diaInicio, $mesInicio, $anioInicio, $diaFin,$mesFin,$anioFin){
        
        $fechaInicio= new DateTime($anioInicio."-".$mesInicio."-".$diaInicio." 00:00:00");
        $fechaFin= new DateTime($anioFin."-".$mesFin."-".$diaFin." 23:00:00");

        $ultimoFolio=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','FOLIO_ACTUAL')->first();
        $precioAlquiler=DB::table('cuartosconfiguracion')->select('valor')->where('nombre','Precio_Alquiler_Cuarto')->first();
        $datosEmpresa=$this->getDatosEmpresa();

        $alquileres=$this->CorteCajaData($fechaInicio,$fechaFin);

        $firstFolio=$alquileres->sortBy('folio')->first()->folio;
        $lastFolio=$alquileres->sortBy('folio')->last()->folio;


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

        $fechaTicket=new DateTime();

        $obj=[
            'nombreEmpresa'=>$datosEmpresa['nombreEmpresa'],
            'direccionCalle'=>$datosEmpresa['direccionCalle'],
            'direccionNumero'=>$datosEmpresa['direccionNumero'],
            'direccionEstatoCiudad'=>$datosEmpresa['direccionEstatoCiudad'],
            'direccionCP'=>$datosEmpresa['direccionCP'],
            'telefono'=>$datosEmpresa['telefono'],
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
            'fechaTicket'=>$fechaTicket->format('d/M/Y H:i:s'),
            'fechaInicioSel'=>$fechaInicio->format('d/M/Y H:i:s'),
            'fechaFinSel'=>$fechaFin->format('d/M/Y H:i:s'),
            'folioInicio'=>$firstFolio,
            'folioFin'=>$lastFolio,
            'totalTickets'=>($alquileres->count()>0) ? $alquileres->count() : 0,
            'data'=>$alquileres
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
                    'cancelaciones.aprobado as cancelacionAprobada'
                    )
        ->where('cuartosalquiler.fecha_entrada','>=',$fechaInicio)
        ->where('cuartosalquiler.fecha_salida','<=',$fechaFin)
        ->where('cuartosalquiler.total_pagado','>',0)
        ->whereNull('cuartosalquiler.fecha_eliminado')
        ->get();

    }
}