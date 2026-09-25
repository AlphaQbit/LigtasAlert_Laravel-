<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class FacilityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(config('facilities'));
    }
}
