<?php

namespace App\Models\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class EscalaImport implements ToArray
{
    // /**
    //  * @param array $row
    //  *
    //  * @return User|null
    //  */
    // public function model(array $row)
    // {
    //     return new User([
    //        'name'     => $row[0],
    //        'email'    => $row[1],
    //        'password' => Hash::make($row[2]),
    //     ]);
    // }

	public function array(array $row) {
		return $row;
	}
}
