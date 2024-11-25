<?php

namespace App\Jobs;

use App\Http\Controllers\TicketController;
use App\Services\PrinterService;
use App\Services\PrintLayoutService;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CorteCajaJob
{

     /**@var PrinterService  */
     private PrinterService $printerService;
     /**@var PrintLayoutService  */
     private PrintLayoutService $printerLayoutService; 

    public function __construct() {
        $this->printerService = new PrinterService();
        $this->printerLayoutService= new PrintLayoutService();
    }

    public function __invoke()
    {
        $this->SetUserAdminToExceJobs();
        $timezone = new DateTimeZone('America/Mexico_City'); // UTC-6
        $TicketController= new TicketController($this->printerService,$this->printerLayoutService);
        $fechaInicio =(new DateTime('now',$timezone))->setTime(0, 0)->format('Y-m-d H:i:s');
        $fechaFin = (new DateTime('now',$timezone))->setTime(23, 59,)->format('Y-m-d H:i:s');
        $request = new Request([ 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin ]);

        try {
            date_default_timezone_set('America/Mexico_City');
            $response=$TicketController->PrintCorteCajaResumen($request,false);
        } catch (\Throwable $th) {
            //throw $th;
            Log::critical('error runing job CorteCajaJob::__invoke');
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
        }
       
    }

    private function SetUserAdminToExceJobs()  {
        $user= DB::table('usuarios')->where('usuario','admin')->first();
        if(!isset($_SESSION['user'])){
            $_SESSION['user']=serialize($user);
        } else{
            $userSession= unserialize($_SESSION['user']);
            if($user->id!=$userSession->id){
                $_SESSION['user']=serialize($user);
            }
        }
    }
}
