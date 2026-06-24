<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;

class ProfessorController extends Controller
{
    public function show()
    {
        $professor = Professor::first();

        if (! $professor) {
            return response()->json(null, 204);
        }

        return response()->json($professor);
    }
}
