<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddStatusCuartos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cuartoestatus')->insert([
            ['estatus'=>'Activo','created_at'=>new DateTime('now')],
            ['estatus'=>'Supendido','created_at'=>new DateTime('now')],
            ['estatus'=>'Inactivo','created_at'=>new DateTime('now')]
        ]);
        
    }
}
