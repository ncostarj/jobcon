<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Newton Gonzaga Costa',
                'email' => 'ncosta.rj@gmail.com',
                'email_comercial' => 'newton.costa@oliveiratrust.com.br',
                'password' => Hash::make('Senh@123')
            ],
        ]
        );
    }
}
