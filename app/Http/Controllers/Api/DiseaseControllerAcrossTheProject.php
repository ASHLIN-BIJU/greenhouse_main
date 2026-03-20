<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiseaseControllerAcrossTheProject extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Disease::all());
    }

    public function show($id)
    {
        $disease = \App\Models\Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        return response()->json($disease);
    }
}
