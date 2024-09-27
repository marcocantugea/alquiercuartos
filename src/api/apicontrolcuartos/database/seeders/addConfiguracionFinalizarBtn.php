<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class addConfiguracionFinalizarBtn extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'6110f89c','variable'=>'FUNC_Btn_Finalizar_Activo','valor'=>'0','created_at'=>new DateTime('now')]
        ]);
    }
}
