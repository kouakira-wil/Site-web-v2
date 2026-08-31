<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
use App\Livewire\Admin\AdminSearch;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Messages\MessageIndex;
use App\Livewire\Admin\Newsletter\SubscriberIndex;
use App\Livewire\Admin\Pages\PageHeaderManager;
use App\Livewire\Admin\Posts\PostForm;
use App\Livewire\Admin\Posts\PostIndex;
use App\Livewire\Admin\Projects\ProjectForm;
use App\Livewire\Admin\Projects\ProjectIndex;
use App\Livewire\Admin\Team\TeamForm;
use App\Livewire\Admin\Testimonials\TestimonialForm;
use App\Livewire\Admin\Testimonials\TestimonialIndex;
use App\Livewire\Admin\Team\TeamIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/l-ecole', AboutController::class)->name('about');

Route::get('/filieres', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/filiere/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/journal', [PostController::class, 'index'])->name('posts.index');
Route::get('/journal/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/equipe', [TeamController::class, 'index'])->name('team');
Route::get('/equipe/{member:slug}', [TeamController::class, 'show'])->name('team.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');

Route::get('/recherche', SearchController::class)->name('search');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('newsletter.store');

/*
|--------------------------------------------------------------------------
| Back office
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'staff',
])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/projets', ProjectIndex::class)->name('projects.index');
    Route::get('/projets/nouveau', ProjectForm::class)->name('projects.create');
    Route::get('/projets/{project}/edition', ProjectForm::class)->name('projects.edit');

    Route::get('/articles', PostIndex::class)->name('posts.index');
    Route::get('/articles/nouveau', PostForm::class)->name('posts.create');
    Route::get('/articles/{post}/edition', PostForm::class)->name('posts.edit');

    Route::get('/equipe', TeamIndex::class)->name('team.index');
    Route::get('/equipe/nouveau', TeamForm::class)->name('team.create');
    Route::get('/equipe/{member}/edition', TeamForm::class)->name('team.edit');

    Route::get('/temoignages', TestimonialIndex::class)->name('testimonials.index');
    Route::get('/temoignages/nouveau', TestimonialForm::class)->name('testimonials.create');
    Route::get('/temoignages/{testimonial}/edition', TestimonialForm::class)->name('testimonials.edit');

    Route::get('/en-tetes', PageHeaderManager::class)->name('pages.index');

    Route::get('/recherche', AdminSearch::class)->name('search');

    Route::get('/messages', MessageIndex::class)->name('messages.index');
    Route::get('/lettre-information', SubscriberIndex::class)->name('newsletter.index');
});

// Jetstream's own dashboard link points here; keep it pointing at the back office.
Route::redirect('/dashboard', '/admin')->name('dashboard');
