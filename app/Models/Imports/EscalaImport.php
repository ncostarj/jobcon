<?php

namespace App\Models\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class EscalaImport implements ToArray
{

	public function array(array $row)
	{
		return $row;
	}
}
