<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddBtnConfigPrintAgain extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'d27ce824','variable'=>'BTN_REIMPRIMIR_TIKET_ACTIVAR','valor'=>'0','created_at'=>new DateTime('now')]
        ]);
    }
}
