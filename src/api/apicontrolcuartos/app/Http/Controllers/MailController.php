<?php
namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MailController extends Controller
{
    public function mail() {

        if(!$this->userHasRoles(['Administrador'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $mailToSend= DB::table('configuraciones')->select('valor')->where('variable','EMAIL_TO_ENVIAR_CORTE')->whereNull('fecha_eliminado')->first();
        $mailToSendName=DB::table('configuraciones')->select('valor')->where('variable','EMAIL_TO_ENVIAR_CORTE_NOMBRE')->whereNull('fecha_eliminado')->first();
        $mailCCSend= DB::table('configuraciones')->select('valor')->where('variable','EMAIL_CC_ENVIAR_CORTE')->whereNull('fecha_eliminado')->first();

        $data=[
            'nombreEmpresa'=>"Prueba de sistema",
            'direccionCalle'=>"Prueba de sistema",
            'direccionNumero'=>"Prueba de sistema",
            'direccionEstatoCiudad'=>"Prueba de sistema",
            'direccionCP'=>"Prueba de sistema",
            'telefono'=>"Prueba de sistema",
            'fechaTicket'=>(new DateTime())->format("d/m/Y H:i:s"),
            'fechaInicioSel'=>(new DateTime())->format("d/m/Y H:i:s"),
            'fechaFinSel'=>(new DateTime())->format("d/m/Y H:i:s"),
            'precioAlquiler'=>"0.00",
            'ultimoFolioAlquiler'=>1,
            'folioCorte'=>1,
            'usuario'=>"caja",
            'MontoTotal'=>"0.00",
            'folioInicio'=>(new DateTime())->format("d/m/Y H:i:s"),
            'folioFin'=>(new DateTime())->format("d/m/Y H:i:s"),
            'totalTickets'=>0,
            'entradas'=>0,
            'salidas'=>0,
            'alquileresPendientes'=>0,
            'de0a1'=>0,
            'de0a1Monto'=>0,
            'de1a2'=>0,
            'de1a2Monto'=>0,
            'de2a3'=>0,
            'de2a3Monto'=>0,
            'masde3'=>0,
            'masde3Monto'=> 0,
            'data'=>[]
        ];
        Mail::send("mail", $data, function($message) use(&$mailToSend, &$mailToSendName,&$mailCCSend) {
        $message->to($mailToSend->valor, $mailToSendName->valor)
        ->cc($mailCCSend->valor)
        ->subject("Test Mail from Sistema Alquileres");
        $message->from("info.controlalquilersys@gmail.com","SistemaControlAlquier");
        });

    }
}