<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class addUlimitedCuartosAlquiler extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cuartos')->whereNull('fecha_eliminado')->update(['fecha_eliminado'=>new DateTime()]);

        for ($i=1; $i <= 300; $i++) { 
            DB::table('cuartos')->insert([
                ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'00'.$i,"descripcion"=>'00'.$i,'estatusId'=>1],
            ]);
        }
    }
}
