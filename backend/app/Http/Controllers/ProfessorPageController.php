<?php

namespace App\Http\Controllers;

use App\Models\Professor;

class ProfessorPageController extends Controller
{
    public function index()
    {
        $professor = Professor::first();

        return view('professor.index', ['professor' => $professor, 'showWipe' => true]);
    }
}
