<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $actions = [
            [
                'action_id' => null,
                'texto' => 'Home',
                'url' => '/home',
                'route_name' => 'home',
                'ordem' => 1
            ],
            [
                'action_id' => null,
                'texto' => 'Logout',
                'url' => '/logout',
                'route_name' => 'auth.logout',
                'ordem' => 2
            ],
            [
                'action_id' => null,
                'texto' => 'Jobs',
                'url' => '#',
                'route_name' => '',
                'ordem' => '3'
            ],
            [
                'action_id' => 2,
                'texto' => 'Dashboard',
                'url' => '/jobs/dashboard',
                'route_name' => 'jobs.dashboard.index',
                'ordem' => 1
            ],
            [
                'action_id' => 2,
                'texto' => 'Pontos',
                'url' => '/jobs/pontos',
                'route_name' => 'jobs.pontos.index',
                'ordem' => 2
            ],
            [
                'action_id' => 2,
                'texto' => 'Contaracheques',
                'url' => '/jobs/contracheques',
                'route_name' => 'jobs.contracheques.index',
                'ordem' => 3
            ],
            [
                'action_id' => 2,
                'texto' => 'Ferias',
                'url' => '/jobs/ferias',
                'route_name' => 'jobs.ferias.index',
                'ordem' => 4
            ],
        ];

        foreach ($actions as $key => $action) {
            
            // dd($action);
            
            $actions[$key]['id'] = Str::uuid()->toString();

            if ($action['action_id']) {
            
                // dd($actions[$action['action_id']]['id']);
            
                $actions[$key]['action_id'] = $actions[$action['action_id']]['id'];
            
            }
            
            $action = $actions[$key];

            DB::table('actions')->insert($action);
        }
    }
}
