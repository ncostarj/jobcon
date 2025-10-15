<?php

namespace App\Domain\Jobs\Resources;

use Illuminate\Support\Collection;

class AnoResource
{
	public static function toArray(Collection $collection)
	{
		return $collection->map(function ($item) {
			return $item;
		})->toArray();
	}
}
