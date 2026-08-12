<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditModeController extends Controller
{
    public function toggle(Request $request)
    {
        $current = session('edit_mode', false);
        session(['edit_mode' => ! $current]);

        return response()->json(['edit_mode' => ! $current]);
    }
}
