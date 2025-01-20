<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function __construct(private Sector $sector){}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Run the database seeds.
         */

        $sectors = [
                [
                    'name' => 'SEGER',
                    'department_id' => 1
                ],
                [
                    'name' => 'SEPRO',
                    'department_id' => 1
                ],
                [
                    'name' => 'SEFIS',
                    'department_id' => 2
                ],
                [
                    'name' => 'SESTAG',
                    'department_id' => 2
                ],
                [
                    'name' => 'SEJUR',
                    'department_id' => 2
                ]
        ];
        foreach ($sectors as $sector) {
            $this->sector->create($sector);
        }
    }
}