<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddEmailCounterConfig extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'e4286f24','variable'=>'EMAIL_ENVIADOS_HOY','valor'=>'0','created_at'=>new DateTime('now')]
        ]);
    }
}
