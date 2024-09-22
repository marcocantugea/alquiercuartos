<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class AddEmailConfiguration extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['publicId'=>'b97586d1','variable'=>'EMAIL_ENVIAR_CORTE','valor'=>'0','created_at'=>new DateTime()],
            ['publicId'=>'de2949b8','variable'=>'EMAIL_TO_ENVIAR_CORTE','valor'=>'info.controlalquilersys@gmail.com','created_at'=>new DateTime()],
            ['publicId'=>'9e6ae7d4','variable'=>'EMAIL_TO_ENVIAR_CORTE_NOMBRE','valor'=>'Marco Cantu','created_at'=>new DateTime()],
            ['publicId'=>'6a20894f','variable'=>'EMAIL_CC_ENVIAR_CORTE','valor'=>'info.controlalquilersys@gmail.com','created_at'=>new DateTime()]
        ]);
    }
}
