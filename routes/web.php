<?php

use App\Http\Controllers\Settings\ProfileController as SettingsProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PasswordController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Redirigir dashboard a posts
Route::get('/dashboard', function () {
    return redirect()->route('post.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas de perfil (configuración) - usando tu archivo Settings/Profile.vue
Route::middleware('auth')->group(function () {
    Route::get('/profile', [SettingsProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [SettingsProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [SettingsProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [SettingsProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});


// Rutas de comentarios
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Rutas de likes
Route::middleware('auth')->group(function () {
    Route::post('/post/{post}/like', [LikeController::class, 'toggle'])
        ->name('post.like');
});

// Rutas de usuarios y perfiles públicos
Route::get('/users/{user}', [UserProfileController::class, 'show'])
    ->name('users.profile');


// Rutas del sistema de seguidores
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])
        ->name('users.follow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])
        ->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])
        ->name('users.following');
    Route::get('/users/suggestions', [FollowController::class, 'suggestions'])
        ->name('users.suggestions');
});


// Cambiar estas rutas de posts:
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/post', [App\Http\Controllers\PostController::class, 'index'])->name('post.index');
    Route::get('/post/create', [App\Http\Controllers\PostController::class, 'create'])->name('post.create');
    Route::post('/post', [App\Http\Controllers\PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}', [App\Http\Controllers\PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [App\Http\Controllers\PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{id}', [App\Http\Controllers\PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}', [App\Http\Controllers\PostController::class, 'destroy'])->name('post.destroy');
});
