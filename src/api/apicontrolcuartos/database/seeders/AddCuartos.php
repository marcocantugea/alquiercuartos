<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCuartos extends Seeder
{

    public function run(): void
    {
        DB::table('cuartos')->insert([
            ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'Ekdi48','estatusId'=>1],
            ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'rTco4u','estatusId'=>1],
            ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'F49dt8','estatusId'=>1],
            ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'Ckidi4','estatusId'=>1],
            ['publicId'=> uniqid(), 'created_at'=>new DateTime('now'),'codigo'=>'Xckdjg','estatusId'=>1],
        ]);
    }
}
