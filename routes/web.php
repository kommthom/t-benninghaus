<?php

use App\Http\Controllers\User\DestroyUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Home
Route::livewire('/', 'pages::posts.index')->name('root');

require __DIR__.'/auth.php';

// Member-related pages
Route::middleware('auth')->prefix('/users')->group(function () {
    Route::livewire('/{id}', 'pages::users.show')
        ->name('users.show')
        ->withoutMiddleware('auth');

    Route::get('/{user}/destroy', DestroyUserController::class)
        ->name('users.destroy')
        ->withoutMiddleware('auth');
});

Route::middleware('auth')->prefix('/settings/users')->group(function () {
    Route::livewire('/{id}/edit', 'pages::settings.users.edit')->name('settings.users.edit');
    Route::livewire('/{id}/destroy', 'pages::settings.users.destroy')->name('settings.users.destroy');

    Route::livewire('/{id}/password/edit', 'pages::settings.users.password.edit')
        ->name('settings.users.password.edit');

    Route::livewire('/{id}/passkeys/edit', 'pages::settings.users.passkeys.edit')
        ->name('settings.users.passkeys.edit');
});

// Article list and content
Route::prefix('/posts')->group(function () {
    Route::livewire('/', 'pages::posts.index')->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::livewire('/create', 'pages::posts.create')->name('posts.create');
        Route::livewire('/{id}/edit', 'pages::posts.edit')->name('posts.edit');
    });

    // The question mark in {slug?} means that the parameter is optional
    Route::livewire('/{id}/{slug?}', 'pages::posts.show')->name('posts.show');
});

// Notification list
Route::livewire('/notifications', 'pages::notifications.index')
    ->middleware('auth')
    ->name('notifications.index');

// Article category
Route::livewire('/categories/{id}/{name?}', 'pages::categories.show')
    ->name('categories.show');

// Article tags
Route::livewire('/tags/{id}', 'pages::tags.show')
    ->name('tags.show');

Route::livewire('/comments/{id}', 'pages::comments.show')
    ->name('comments.show');

// Web Feed
Route::feeds();
