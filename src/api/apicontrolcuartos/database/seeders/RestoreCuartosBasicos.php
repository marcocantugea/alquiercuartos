<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestoreCuartosBasicos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cuartos')->whereNull('fecha_eliminado')->update(['fecha_eliminado'=>new \DateTime()]);
        $AddCuartos= new AddCuartos();
        $AddCuartos->run();
    }
}
