<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function __construct(private User $user){}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->create([
            'name'  => 'Administrador',
            'email' => 'admin@admin.com',
            'position' => 'Administrative assistant',
            'password' => bcrypt('123456') 
        ]);
    }
}
