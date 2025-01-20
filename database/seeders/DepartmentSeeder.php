<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function __construct(private Department $department){}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Run the database seeds.
         */

        $departments = [
                [
                    'name' => 'Tecnologia da Informação',
                ],
                [
                    'name' => 'Secretaria',
                ]
        ];
        foreach ($departments as $department) {
            $this->department->create($department);
        }
    }
}
