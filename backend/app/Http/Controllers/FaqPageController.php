<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqPageController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', true)->orderBy('order_index')->get();
        $categories = $faqs->pluck('category')->filter()->unique()->values();
        $uncategorized = $faqs->whereNull('category')->values();

        return view('faq.index', compact('faqs', 'categories', 'uncategorized') + ['showWipe' => true]);
    }
}
