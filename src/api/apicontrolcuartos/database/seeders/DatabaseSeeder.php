<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            AddStatusCuartos::class,
            AddCuartos::class,
            AddConfiguracionCuartos::class,
            AddRoles::class,
            AddUsuariosBase::class,
            AddBaseConfiguracion::class,
            AddPrinterConfiguration::class,
            AddEmailConfiguration::class,
            addConfiguracionFinalizarBtn::class,
            AddConfigAutoScheduler::class,
            AddEmailCounterConfig::class,
            AddBtnConfigPrintAgain::class,
            AddSetPassWordForPrintCopies::class
        ]);
    }
}
