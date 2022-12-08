<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $users = [
        [
            'name' => 'administrador',
            'email' => 'admin@gmail.com',
            'password' => '0000'
        ],
        [
            'name' => 'darwin',
            'email' => 'darwin@gmail.com',
            'password' => '0000'
        ],
        [
            'name' => 'max',
            'email' => 'max@gmail.com',
            'password' => '0000'
        ]
    ];

    public function run()
    {
        foreach ($this->users as $user) { 
            
            do {
                $token = Str::uuid();
            } while (User::where("token", $token)->first() instanceof User);
            
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'token' => $token
            ]);
        }
        // User::create([
        //     'name' => 'Administrador',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('0000'),
        //     'token' => $token
        // ]);
    }
}
