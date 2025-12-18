<?php

namespace Database\Seeders;

use App\Domain\Jobs\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('role_user')->insert(
            [
                [
                    'user_id' => User::where('name', 'like', '%newton%')->first()->id,
                    'role_id' => Role::where('nome', 'like', '%admin%')->first()->id,
                ],
                [
                    'user_id' => User::where('name', 'like', '%newton%')->first()->id,
                    'role_id' => Role::where('nome', 'like', '%gestor%')->first()->id,
                ],
                [
                    'user_id' => User::where('name', 'like', '%newton%')->first()->id,
                    'role_id' => Role::where('nome', 'like', '%colaborador%')->first()->id,
                ],
            ]
        );
    }
}
