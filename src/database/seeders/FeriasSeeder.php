<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeriasSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ferias')->insert([
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2024-09-23',
				'fim' => '2024-09-27',
				'qtd_dias' => 5,
				'observacao' => 'Campos do Jordão com os amigos e as crianças',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2024-08-05',
				'fim' => '2024-08-19',
				'qtd_dias' => 15,
				'observacao' => 'Petrópolis com a família (Eu, Carol, Diana e Marli)',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2024-02-14',
				'fim' => '2024-02-28',
				'qtd_dias' => 15,
				'observacao' => '',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2023-07-24',
				'fim' => '2023-08-07',
				'qtd_dias' => 15,
				'observacao' => '',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2023-02-22',
				'fim' => '2023-03-08',
				'qtd_dias' => 15,
				'observacao' => '',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2022-09-05',
				'fim' => '2022-09-19',
				'qtd_dias' => 15,
				'observacao' => '',
				'created_at' => date('Y-m-d H:i:s')
			],
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'inicio' => '2022-02-14',
				'fim' => '2022-02-28',
				'qtd_dias' => 15,
				'observacao' => 'Le Cantón (Teresópolis) com a família (Eu, Carol, Diana e Marli)',
				'created_at' => date('Y-m-d H:i:s')
			],
		]);
	}
}
