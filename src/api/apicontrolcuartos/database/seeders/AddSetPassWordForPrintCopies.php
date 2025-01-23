<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddSetPassWordForPrintCopies extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'9584eed2','variable'=>'BTN_REIMPRIMIR_TIKET_ACTIVAR_CONTRASENA','valor'=>'0','created_at'=>new DateTime()],
            ['publicId'=>'e3d7dd36','variable'=>'BTN_REIMPRIMIR_TIKET_CONTRASENA','valor'=>'SuperStar','created_at'=>new DateTime()],
        ]);
    }
}
