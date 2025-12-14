<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testConnection()
    {
        return response()->json([
            'message' => 'Connexion entre backend et frontend avec succès!',
            'status' => 'success',
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}
