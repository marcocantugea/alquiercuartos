<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddConfiguracionCuartos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cuartosconfiguracion')->insert([
            ['publicId'=>'04f8d3be','nombre'=>'Tiempo_Renta_Cuarto_Min','valor'=>'20','created_at'=>new DateTime('now')],
            ['publicId'=>'04f8d602','nombre'=>'Tiempo_Tolerancia_Min','valor'=>'3','created_at'=>new DateTime('now')],
            ['publicId'=>'04f8d6fc','nombre'=>'Precio_Alquiler_Cuarto','valor'=>'50.00','created_at'=>new DateTime('now')],
            ['publicId'=>'a6b31638','nombre'=>'FOLIO_ACTUAL','valor'=>'1502','created_at'=>new DateTime('now')],
        ]);
    }
}
