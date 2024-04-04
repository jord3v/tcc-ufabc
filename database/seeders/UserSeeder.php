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
            'name'  => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456') 
        ]);
    }
}
