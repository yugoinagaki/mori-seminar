<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index', ['showWipe' => true, 'title' => 'お問い合わせ']);
    }
}
