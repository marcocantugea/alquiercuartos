<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddUsuariosBase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $key= (!empty($_ENV['APP_KEY'])) ? $_ENV['APP_KEY'] : "";
        $options= [
            'cost'=>8
        ];
         DB::table('usuarios')->insert([
            ['publicId'=>'3382e386','usuario'=>'admin','password_hash'=>password_hash('eleonor'.$key,PASSWORD_DEFAULT,$options),'created_at'=>new DateTime('now'),'roleId'=>1],
            ['publicId'=>'3382e85e','usuario'=>'supervisor','password_hash'=>password_hash('athena'.$key,PASSWORD_DEFAULT,$options),'created_at'=>new DateTime('now'),'roleId'=>2],
            ['publicId'=>'3382e9f8','usuario'=>'caja','password_hash'=>password_hash('eros'.$key,PASSWORD_DEFAULT,$options),'created_at'=>new DateTime('now'),'roleId'=>3]
         ]);
    }
}
