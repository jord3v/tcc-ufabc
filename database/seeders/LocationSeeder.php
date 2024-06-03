<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function __construct(private Location $location){}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = ['AMERICANA', 'ARAÇATUBA', 'BAURU', 'CAMPINAS', 'CARAGUATATUBA', 'CENTRO', 'DEPÓSITO', 'FRANCA', 'GUARULHOS', 'ITU', 'JUNDIAÍ', 'MARÍLIA', 'OSASCO', 'PIRACICABA', 'PRAIA GRANDE', 'PRESIDENTE PRUDENTE', 'RIBEIRÃO PRETO', 'RIO CLARO', 'SANTO ANDRÉ', 'SANTOS', 'SÃO CARLOS', 'SÃO JOSÉ DO RIO PRETO', 'SÃO JOSÉ DOS CAMPOS', 'SOROCABA', 'SEDE'];
        foreach ($locations as $key => $location) {
            /*$this->location->create([
                'name'  => $location,
                'user_id' => 1
            ]);*/
        }
    }
}
