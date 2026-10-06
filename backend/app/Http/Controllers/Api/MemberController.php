<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::with('cohort')
            ->leftJoin('cohorts', 'members.cohort_id', '=', 'cohorts.id')
            ->orderByDesc('cohorts.generation')
            ->orderBy('members.created_at')
            ->select('members.*')
            ->get();

        return response()->json($members);
    }
}
