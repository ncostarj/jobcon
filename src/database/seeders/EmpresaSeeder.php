<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('empresas')->insert([
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'razao_social'=>'Oliveira Trust Dtvm Sa',
				'estabelecimento'=>'DTVM Matriz',
				'cnpj'=>'36113876/0001-91',
				'created_at' => date('Y-m-d H:i:s')
			],
		]);
    }
}
