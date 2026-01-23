<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;

class ContrachequeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		DB::table('contracheques')->insert([
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'empresa_id' => Empresa::where('cnpj', '36113876/0001-91')->first()->id,
				'competencia' => '2024-07-25',
				'tipo' => 'regular',
				'salario_base' => 10,
				'salario_liquido' => 10,
				'total_vencimentos' => 10,
				'total_descontos' => 10,
				'comprovante'=> null
			],
		]);
    }
}
