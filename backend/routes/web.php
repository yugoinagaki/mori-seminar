<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfessorPageController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/theme', [ThemeController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);
Route::get('/members', [MembersController::class, 'index']);
Route::get('/professor', [ProfessorPageController::class, 'index']);
Route::get('/case-studies', [CaseStudyPageController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyPageController::class, 'show']);
Route::get('/faq', [FaqPageController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
