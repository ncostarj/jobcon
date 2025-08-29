<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EscalaSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//

		DB::table('escalas')
			->insert([
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-18',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-19',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-21',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-24',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-25',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-02-26',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-05',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-07',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-11',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-12',
					'equipe' => 1,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-14',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-17',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-19',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-21',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-25',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-26',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-03-28',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-04-01',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-04-02',
					'equipe' => 0,
				],
				[
					'id' => Str::uuid()->toString(),
					'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
					'dia' => '2025-04-04',
					'equipe' => 0,
				],
			]);
	}
}
