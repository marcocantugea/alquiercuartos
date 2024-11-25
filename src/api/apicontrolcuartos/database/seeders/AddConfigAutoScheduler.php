<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddConfigAutoScheduler extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //AUTOAJENDA_HRS_ENVIO_CORTE AUTOAJENDA_ACTIVAR
        DB::table('configuraciones')->insert([
            ['publicId'=>'e3b0c442','variable'=>'AUTOAJENDA_HRS_ENVIO_CORTE','valor'=>'20:20','created_at'=>new DateTime('now')],
            ['publicId'=>'52f51654','variable'=>'AUTOAJENDA_ACTIVAR','valor'=>'0','created_at'=>new DateTime('now')]
        ]);
    }
}
