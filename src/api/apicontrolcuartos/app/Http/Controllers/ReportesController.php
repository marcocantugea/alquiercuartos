<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


final class ReportesController extends Controller
{

    public function getMontosTotalesPorMes(Request $request){

        if(!$this->userHasRoles(['Administrador','Supervisor'])) return new Response($this->stdResponse(false,true,'usario no tiene permisos'),401);

        $yearSelected=$request->query('anio');
        if(!is_numeric($yearSelected)) return new Response($this->stdResponse(false,true,'invalid year'),401);
                
        $monthsValues=[];

        for($i=1; $i <=12 ; $i++) { 

            $fechaInicio=$yearSelected."-".$i."-01 00:00:00";

            $fechaFinCal = new DateTime($yearSelected."-".$i."-01");
            $fechaFinCal->modify('last day of this month');
            $fechaFin= $fechaFinCal->format('Y-m-d') ." 23:59:59" ;

            $totalAcumulado=DB::table('cuartosalquiler')->whereNull('fecha_eliminado')->where('fecha_entrada','>=',$fechaInicio)->where('fecha_salida','<=', $fechaFin)->sum('total_pagado');

            //$monthsValues[]=number_format($totalAcumulado,2);
            $monthsValues[]=$totalAcumulado;
        }

        return new Response($this->stdResponse(data:$monthsValues));

    }
    
}
