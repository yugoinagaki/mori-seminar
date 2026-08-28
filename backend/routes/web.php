<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\EditModeController;
use App\Http\Controllers\FaqPageController;
use App\Http\Controllers\InlineCreateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfessorPageController;
use App\Http\Controllers\ThemeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// ─── Inline edit (admin only) ─────────────────────────────────────────────────
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::post('/edit-mode/toggle', [EditModeController::class, 'toggle'])
        ->middleware('throttle:30,1')
        ->name('edit-mode.toggle');

    Route::post('/drafts', [DraftController::class, 'store'])
        ->middleware('throttle:120,1')
        ->name('drafts.store');

    Route::post('/drafts/apply',   [DraftController::class, 'apply'])
        ->middleware('throttle:10,1')
        ->name('drafts.apply');
    Route::post('/drafts/discard', [DraftController::class, 'discard'])
        ->middleware('throttle:10,1')
        ->name('drafts.discard');

    Route::middleware('throttle:20,1')->group(function () {
        Route::post('/create/post',        [InlineCreateController::class, 'post'])->name('create.post');
        Route::post('/create/case-study',  [InlineCreateController::class, 'caseStudy'])->name('create.case-study');
        Route::post('/create/member',      [InlineCreateController::class, 'member'])->name('create.member');
    });
});

Route::get('/', [HomeController::class, 'index']);

Route::middleware('page-visible:theme')->group(function () {
    Route::get('/theme', [ThemeController::class, 'index']);
});

Route::middleware('page-visible:news')->group(function () {
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{slug}', [NewsController::class, 'show']);
});

Route::middleware('page-visible:blog')->group(function () {
    Route::get('/blog', [BlogController::class, 'index']);
    Route::get('/blog/{slug}', [BlogController::class, 'show']);
});

Route::middleware('page-visible:members')->group(function () {
    Route::get('/members', [MembersController::class, 'index']);
});

Route::middleware('page-visible:professor')->group(function () {
    Route::get('/professor', [ProfessorPageController::class, 'index']);
});

Route::middleware('page-visible:case_studies')->group(function () {
    Route::get('/case-studies', [CaseStudyPageController::class, 'index']);
    Route::get('/case-studies/{slug}', [CaseStudyPageController::class, 'show']);
});

Route::middleware('page-visible:faq')->group(function () {
    Route::get('/faq', [FaqPageController::class, 'index']);
});

Route::middleware('page-visible:contact')->group(function () {
    Route::get('/contact', [ContactController::class, 'index']);
});
