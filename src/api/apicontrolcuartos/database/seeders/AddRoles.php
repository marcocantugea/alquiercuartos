<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['publicId'=>'d088927c','rol'=>'Administrador','created_at'=>new DateTime('now')],
            ['publicId'=>'d0889592','rol'=>'Supervisor','created_at'=>new DateTime('now')],
            ['publicId'=>'d0889718','rol'=>'Caja','created_at'=>new DateTime('now')],
        ]);
    }
}
