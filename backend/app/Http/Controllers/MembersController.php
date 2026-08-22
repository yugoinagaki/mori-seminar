<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $members = Member::with('cohort')
            ->orderBy('order_index')
            ->get();

        $cohorts = Cohort::orderBy('generation', 'desc')->get();

        return view('members.index', [
            'members'  => $members,
            'cohorts'  => $cohorts,
            'showWipe' => true,
        ]);
    }
}
