<?php

use App\Http\Controllers\Aicontroller;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PreInstructionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


// google authentication
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');
Route::get('auth/google/callback', [IndexController::class, 'googleAuth']);

Route::middleware(['if.auth'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');    
});

Route::get('/signup', [IndexController::class, 'signup'])->name('signup');
Route::get('/sign-in', [IndexController::class, 'signIn'])->name('sign-in');
Route::post('/save-data-into-session', [IndexController::class, 'saveSession'])->name('save.session');
Route::get('/about', [IndexController::class, 'about'])->name('about');
Route::get('/terms', [IndexController::class, 'terms'])->name('terms');
Route::get('/privacy', [IndexController::class, 'privacy'])->name('privacy');
Route::get('/getRecommendationFromAI' , [Aicontroller::class, 'getRecommendation'])->name('ai-recommendation');
Route::post('/ai-recommendation-restaurants' , [Aicontroller::class, 'aiRecommendationRestaurants'])->name('ai-recommendation-restaurants');

Route::get('/getAllAiRecommendation' , [Aicontroller::class, 'getallRecommendation'])->name('get-all-recommendations');
Route::post('/globalFunctionForSaveInDb', [IndexController::class, 'globalFunctionForSaveInDb'])->name('globalFunctionForSaveInDb');

Route::middleware(['check.auth'])->group(function () {
    // ajax routes
    Route::get('/move-to-watchlist', [IndexController::class, 'moveToWatchlist'])->name('moveToWatchlist');
    Route::get('/move-to-shows-i-love-from-friends', [IndexController::class, 'moveToShowsLoveFromFriends'])->name('moveToShowsLoveFromFriends');
    Route::get('/move-to-shows-i-love', [IndexController::class, 'moveToShowsLove'])->name('moveToShowsLove');
    Route::get('/remove-show', [IndexController::class, 'removeShow'])->name('removeShow');
    Route::get('/remove-request', [IndexController::class, 'removeRequest'])->name('removeRequest');
    Route::get('/unfriend', [IndexController::class, 'unfriend'])->name('unfriend');
    Route::post('/save-show-watchlist', [IndexController::class, 'saveShowsInWatchlist'])->name('save-show-watchlist');
    Route::post('/save-show-in-shows-i-love', [IndexController::class, 'saveShowsInShowsILove'])->name('save-show-in-shows-i-love');
    Route::get('/remveshowfromshowsilove', [IndexController::class, 'remveshowfromshowsilove'])->name('remveshowfromshowsilove');
    Route::post('/save-user-name', [IndexController::class, 'saveUserName'])->name('saveUserName');
    Route::get('/delete-user-account', [IndexController::class, 'deleteUserAccount'])->name('delete-user-account');
    Route::post('/header-mark-as-seen', [IndexController::class, 'markHeaderAsSeen'])->name('header.markSeen');
    Route::post('/friends-shows-mark-as-seen', [IndexController::class, 'friendShowsMarkAsSeen'])->name('friendShowsMarkAsSeen');
    Route::get('/ai-recs-to-watchlist', [IndexController::class, 'moveToWatchlistFromAiRecs'])->name('moveToWatchlistFromAiRecs');
    Route::get('/ai-recs-to-showsLove', [IndexController::class, 'moveToShowsloveFromAiRecs'])->name('moveToShowsloveFromAiRecs');
    Route::get('/ai-recs-remove', [IndexController::class, 'removeFromAiRecs'])->name('removeFromAiRecs');
    Route::get('/updateremebertoken', [IndexController::class, 'updateremebertoken'])->name('updateremebertoken');
    Route::get('/moveToBookMarksFromRecs', [IndexController::class, 'moveToBookMarksFromRecommendation'])->name('moveToBookMarksFromRecs');
    Route::get('/moveToBookMarksFromFriends', [IndexController::class, 'moveToBookMarksFromFriends'])->name('moveToBookMarksFromFriends');
    Route::get('/addRestaurantToQueue', [IndexController::class, 'addRestaurantToQueue'])->name('addRestaurantToQueue');
    
    
    
    // application routes
    Route::match(['get', 'post'],'/home', [IndexController::class, 'home'])->name('home');
    Route::get('/friends', [IndexController::class, 'friends'])->name('friends');
    Route::get('/bookmarks', [IndexController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/watch-list', [IndexController::class, 'watchList'])->name('watch-list');
    Route::match(['get', 'post'],'/shows-loved', [IndexController::class, 'showsLoved'])->name('shows-loved'); 
    Route::get('/logout', [IndexController::class, 'logout'])->name('logout'); 
    // show models route
    Route::get('/account/{id?}', [IndexController::class, 'account'])->name('account'); 
    Route::get('/shows-friend-love/{id?}', [IndexController::class, 'friendsShowsList'])->name('friendsShowsList'); 
    Route::get('/restaurants-friend-love/{id?}', [IndexController::class, 'friendsRestaurantList'])->name('friendsRestaurantList'); 
    
    Route::get('/add-friend', [IndexController::class, 'addFriend'])->name('add-friend'); 
    
    Route::get('/add-show', [IndexController::class, 'addShow'])->name('add-show'); 
    Route::get('/add-restaurant-love', [IndexController::class, 'addRestaurantLove'])->name('add-restaurant-love'); 
    Route::get('/add-restaurant-queue', [IndexController::class, 'addSingleRestaurantQueue'])->name('addSingleRestaurantQueue'); 
    
    Route::get('/add-show-watchlist', [IndexController::class, 'addShowInWatchlist'])->name('add-show-watchlist'); 
    Route::get('/how-it-work', [IndexController::class, 'howToWork'])->name('howToWork');
    Route::post('/save-invitations', [IndexController::class, 'saveInvitations'])->name('saveInvitations'); 
});

   // Admin Routes
   Route::match(['get','post'],'/admin',[App\Http\Controllers\AdminController::class,'Admin'])->name('adminlogin');
   Route::match(['get','post'],'/admin/login',[App\Http\Controllers\AdminController::class, 'AdminLogin'])->name('admin.signin');

   Route::group(['middleware' => 'admin'], function () {

    Route::group(['middleware' => 'permission'], function () {

        // For Admin Dashboard 
        Route::match(['get','post'],'/admin/dashboard',[App\Http\Controllers\AdminController::class,'AdminDashboard'])->name('admin.dashboard');

       // Role Module
        Route::match(['get','post'],'/admin/add-role',[App\Http\Controllers\RoleController::class,'AddRole']);
        Route::match(['get','post'],'/admin/view-role',[App\Http\Controllers\RoleController::class,'ViewRole']);
        Route::match(['get','post'],'/admin/edit-role/{id}',[App\Http\Controllers\RoleController::class,'EditRole'])->name('admin.edit-role');
        Route::match(['get','post'],'/admin/delete-role/{id}',[App\Http\Controllers\RoleController::class,'DeleteRole'])->name('admin.delete-role');

        // Admin Module
        Route::match(['get','post'],'/admin/create-admin',[App\Http\Controllers\AdminController::class,'CreateAdmin'])->name('admin.create-admin'); 
        Route::match(['get','post'],'/admin/view-admin',[App\Http\Controllers\AdminController::class,'ViewAdmin'])->name('admin.view-admin');
        Route::match(['get','post'],'/admin/edit-admin/{id}',[App\Http\Controllers\AdminController::class,'EditAdmin'])->name('admin.edit-admin');
        Route::match(['get','post'],'/admin/delete-admin/{id}',[App\Http\Controllers\AdminController::class,'DeleteAdmin'])->name('admin.delete-admin');
        
        // Preinstructions Module
        Route::match(['get', 'post'], '/admin/preinstructions/{user_id}', [PreInstructionController::class, 'Preinstructions'])->name('admin.preinstructions');

        // User Module
        Route::match(['get','post'],'/admin/view-users',[UserController::class,'ViewUsers'])->name('admin.view-users');
        Route::match(['get','post'],'/admin/delete-user/{id}',[UserController::class,'DeleteUser'])->name('admin.delete-user');
        Route::match(['get','post'],'/admin/view-user-friends/{id}',[UserController::class,'ViewUserFriends'])->name('admin.view-user-friends');
        Route::match(['get','post'],'/admin/view-user-shows/{id}/{type}',[UserController::class,'ViewUserShows'])->name('admin.view-user-shows');
        Route::match(['get','post'],'/admin/user-restaurants/{id}',[UserController::class,'UserRestaurants'])->name('admin.user-restaurants');

        Route::match(['get','post'],'/admin/create-setting',[PreInstructionController::class,'CreateSetting'])->name('admin.create-setting'); 
        
    });

       // Admin Logout
       Route::get('/admin/logout',[App\Http\Controllers\AdminController::class,'AdminLogout']);

       // Admin Module 
       Route::match(['get','post'],'/admin/save-admin',[App\Http\Controllers\AdminController::class,'SaveAdmin'])->name('admin.save-admin');
       Route::match(['get','post'],'/admin/update-admin/{id}',[App\Http\Controllers\AdminController::class,'UpdateAdmin']);
       Route::match(['get','post'],'/admin/admin-status',[App\Http\Controllers\AdminController::class,'AdminStatus']);
       Route::match(['get','post'],'/admin/get-admin',[App\Http\Controllers\AdminController::class,'GetAdmin'])->name('admin.get-admin');
        
       // Role Module 
       Route::match(['get','post'],'/admin/get-role',[App\Http\Controllers\RoleController::class,'GetRole']);
       Route::match(['get','post'],'/admin/save-role',[App\Http\Controllers\RoleController::class,'SaveRole']);
       Route::match(['get','post'],'/admin/update-role/{id}',[App\Http\Controllers\RoleController::class,'UpdateRole']);
       Route::match(['get','post'],'/admin/role-status',[App\Http\Controllers\RoleController::class,'RoleStatus']);

       // User Module 
       Route::match(['get','post'],'/admin/get-users',[UserController::class,'GetUsers']);
       Route::match(['get','post'],'/admin/get-user-shows',[UserController::class,'GetUserShows']);
       Route::match(['get','post'],'/admin/get-user-restaurants',[UserController::class,'GetUserRestaurants']);
       Route::match(['get','post'],'/admin/get-user-friends',[UserController::class,'GetUserFriends'])->name('admin.get-user-friends');
       Route::match(['get','post'],'/admin/upload-user-csv',[UserController::class,'UploadUsercsv'])->name('admin.upload-user-csv');

   });
