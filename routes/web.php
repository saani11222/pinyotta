<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


// google authentication
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');
Route::get('auth/google/callback', [IndexController::class, 'googleAuth']);


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/save-data-into-session', [IndexController::class, 'saveSession'])->name('save.session');

Route::get('/signup', [IndexController::class, 'signup'])->name('signup');
Route::get('/sign-in', [IndexController::class, 'signIn'])->name('sign-in');

Route::get('/home', [IndexController::class, 'home'])->name('home');
Route::get('/friends', [IndexController::class, 'friends'])->name('friends');
Route::get('/watch-list', [IndexController::class, 'watchList'])->name('watch-list');
Route::get('/shows-loved', [IndexController::class, 'showsLoved'])->name('shows-loved'); 
Route::get('/logout', [IndexController::class, 'logout'])->name('logout'); 




// show models route
Route::get('/account/{id?}', [IndexController::class, 'account'])->name('account'); 
Route::get('/shows-friend-love/{id?}', [IndexController::class, 'friendsShowsList'])->name('friendsShowsList'); 
Route::get('/add-friend', [IndexController::class, 'addFriend'])->name('add-friend'); 
Route::get('/add-show', [IndexController::class, 'addShow'])->name('add-show'); 
Route::get('/how-it-work', [IndexController::class, 'howToWork'])->name('howToWork'); 
