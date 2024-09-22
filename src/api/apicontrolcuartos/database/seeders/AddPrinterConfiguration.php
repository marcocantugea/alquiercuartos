<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPrinterConfiguration extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'90e428ee','variable'=>'PRINTER_HOST','valor'=>'DESKTOP-RRD415J','created_at'=>new DateTime()],
            ['publicId'=>'3a8e6028','variable'=>'PRINTER_SMB_NAME','valor'=>'EClinePrinter','created_at'=>new DateTime()],
            ['publicId'=>'4f86e96a','variable'=>'PRINTER_TEMPLATE_PATH','valor'=>'../resources/templates/','created_at'=>new DateTime()],
            ['publicId'=>'71f4977d','variable'=>'TICKET_INICIO_ALQUILER','valor'=>'inicio_alquiler.print','created_at'=>new DateTime()],
            ['publicId'=>'095ff598','variable'=>'TICKET_FIN_ALQUILER','valor'=>'fin_alquiler.print','created_at'=>new DateTime()],
            ['publicId'=>'00957a25','variable'=>'TICKET_CORTECAJA_RESUMEN','valor'=>'corte_caja_resumen.print','created_at'=>new DateTime()],
            ['publicId'=>'907e3181','variable'=>'TICKET_CORTECAJA_DETALLE','valor'=>'corte_caja_detalle.print','created_at'=>new DateTime()],
            ['publicId'=>'4fb22c68','variable'=>'TICKET_PRUEBA','valor'=>'test.print','created_at'=>new DateTime()],
        ]);
    }
}
