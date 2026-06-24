<?php

use App\Http\Controllers\Api\AnnualThemeController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfessorController;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;

Route::get('/settings', fn () => response()->json(SiteSetting::instance()));

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::get('/members', [MemberController::class, 'index']);

Route::get('/professor', [ProfessorController::class, 'show']);

Route::get('/annual-themes/latest', [AnnualThemeController::class, 'latest']);

Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

Route::get('/faqs', [FaqController::class, 'index']);
