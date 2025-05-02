<?php

namespace App\Models\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Log;
<<<<<<< Updated upstream
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

=======
use Maatwebsite\Excel\Concerns\WithStartRow;
>>>>>>> Stashed changes
class EscalaImport implements ToArray
{

	// public function startRow(): int
    // {
    //     return 3;
	// }

	public function array(array $row) {
		return $row;
		// return [
		// 	'a'=>1
		// ];
	}
}
