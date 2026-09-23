<?php

use App\Http\Controllers\User\DestroyUserController;
use Illuminate\Support\Facades\Route;
use Laravel\Head\Enums\OgType;

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
    Route::livewire('/{id}/edit', 'pages::settings.users.edit')
        ->name('settings.users.edit')
        ->withHead(title: __('Member Center - Edit Profile'));

    Route::livewire('/{id}/destroy', 'pages::settings.users.destroy')
        ->name('settings.users.destroy')
        ->withHead(title: __('Member Center - Delete Account'));

    Route::livewire('/{id}/password/edit', 'pages::settings.users.password.edit')
        ->name('settings.users.password.edit')
        ->withHead(title: __('Member Center - Change Password'));

    Route::livewire('/{id}/passkeys/edit', 'pages::settings.users.passkeys.edit')
        ->name('settings.users.passkeys.edit');
});

// Article list and content
Route::prefix('/posts')->group(function () {
    Route::livewire('/', 'pages::posts.index')
        ->name('posts.index')
        ->withHead(title: __('All articles'));

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::livewire('/create', 'pages::posts.create')
            ->name('posts.create')
            ->withHead(title: __('Add Article'));

        Route::livewire('/{id}/edit', 'pages::posts.edit')
            ->name('posts.edit')
            ->withHead(title: __('Edit Article'));
    });

    // The question mark in {slug?} means that the parameter is optional
    Route::livewire('/{id}/{slug?}', 'pages::posts.show')
        ->name('posts.show')
        ->withHead(og: ['type' => OgType::Article]);
});

// Notification list
Route::livewire('/notifications', 'pages::notifications.index')
    ->middleware('auth')
    ->name('notifications.index')
    ->withHead(title: __('My Notifications'));

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