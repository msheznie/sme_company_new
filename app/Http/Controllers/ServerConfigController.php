<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerConfigController extends Controller
{
    /**
     * Get server time for timezone synchronization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServerTime()
    {
        return response()->json([
            'time' => time(),
            'timezone' => config('app.timezone', 'UTC'),
            'timestamp' => now()->timestamp
        ]);
    }
}
