<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddBaseConfiguracion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'60e731b8','variable'=>'NOMBRE_EMPRESA','valor'=>'HOTEL RP','created_at'=>new DateTime()],
            ['publicId'=>'f71dc236','variable'=>'EMPRESA_DIRECCION_CALLE','valor'=>'18 PONIENTE','created_at'=>new DateTime()],
            ['publicId'=>'c525c81f','variable'=>'EMPRESA_DIRECCION_NUMERO','valor'=>'508','created_at'=>new DateTime()],
            ['publicId'=>'2ada2a0a','variable'=>'EMPRESA_DIRECCION_ESTADOCIUDAD','valor'=>'PUEBLA,PUEBLA','created_at'=>new DateTime()],
            ['publicId'=>'465933d3','variable'=>'EMPRESA_DIRECCION_CODIGOPOSTAL','valor'=>'72000','created_at'=>new DateTime()],
            ['publicId'=>'c349e83e','variable'=>'EMPRESA_TELEFONO','valor'=>'22227759838','created_at'=>new DateTime()],
        ]);
    }
}
