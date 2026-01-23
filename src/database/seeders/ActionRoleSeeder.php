<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = DB::table('roles')->get();
        $actions = DB::table('actions')->get();

        $lines = [];

        foreach($roles as $role) {
            foreach($actions as $action) {
                $lines[] = [ 'role_id' => $role->id, 'action_id' => $action->id ];
            }
        }

        foreach($lines as $line) {
            DB::table('action_role')->insert($line);   
        }
    }
}
