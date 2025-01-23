<?php

namespace App\Console;

use App\Jobs\CorteCajaJob as JobsCorteCajaJob;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Scheduler
        try{
            $run= boolval($this->RunScheduler());
        
            if($run){
                $setup=$this->GetHours();
                $schedule->call(new JobsCorteCajaJob())->timezone('America/Mexico_City')->dailyAt($setup);
            }
        }catch(\Exception $e){
            
        }
       

    }

    private function RunScheduler(){
        $valor=DB::table('configuraciones')->select('valor')->where('variable','AUTOAJENDA_ACTIVAR')->first();
        return (!empty($valor)) ? $valor->valor : 0;
    }

    private function GetHours(){
        $horas=DB::table('configuraciones')->select('valor')->where('variable','AUTOAJENDA_HRS_ENVIO_CORTE')->first();
        return (!empty($horas)) ? $horas->valor : "";
    }
}
