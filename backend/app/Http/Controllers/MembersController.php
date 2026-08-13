<?php

namespace App\Http\Controllers;

use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $activeMembers = Member::where('status', 'active')->orderBy('order_index')->orderBy('generation', 'desc')->get();
        $alumniMembers = Member::where('status', 'alumni')->orderBy('generation', 'desc')->get();

        return view('members.index', ['activeMembers' => $activeMembers, 'alumniMembers' => $alumniMembers, 'showWipe' => true]);
    }
}
