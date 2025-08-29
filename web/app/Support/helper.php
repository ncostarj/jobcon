<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('logger')) {
    /**
     * Helper para log.
     *
     * @param string $mixed
     */
    function logger($mixed)
    {
        if(!is_string($mixed)) {
            Log::info((array) $mixed);
            return;
        }

        Log::info($mixed);
    }
}
