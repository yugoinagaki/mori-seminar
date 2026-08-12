<?php

namespace App\Http\Controllers;

use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('order_index')
            ->orderBy('generation', 'desc')
            ->get();

        $active = $members->where('status', 'active')->values();
        $obog   = $members->where('status', 'ob_og')->values();

        return view('members.index', array_merge(compact('active', 'obog'), ['showWipe' => true]));
    }
}
