<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $members = Member::with('cohort')
            ->leftJoin('cohorts', 'members.cohort_id', '=', 'cohorts.id')
            ->orderByDesc('cohorts.generation')
            ->orderBy('members.created_at')
            ->select('members.*')
            ->get();

        $cohorts = Cohort::orderBy('generation', 'desc')->get();

        return view('members.index', [
            'members'  => $members,
            'cohorts'  => $cohorts,
            'showWipe' => true,
        ]);
    }
}
