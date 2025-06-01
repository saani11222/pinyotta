<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\AdminModule;
use App\Models\Admin;
use App\Models\RolePermission;
use App\Models\Friend;
use App\Models\Show;
use Illuminate\Support\Facades\Auth;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function boot(): void{
        view()->composer('admin.layouts.sidebar', function ($view) {
            $admin_id = Session::get('adminid');
            $admin = Admin::where('admin_id', $admin_id)->first();
            
            if($admin->type == "2"){
              $modules = AdminModule::with('pages')->orderBy('sorting', 'asc')->get();
            }
            else{

            $role_permissions = RolePermission::where('role_id', $admin->role_id)->pluck('admin_module_page_id')->map(function ($id) {
                return (int) $id;
            });
            $modules = AdminModule::with(['pages' => function ($query) use ($role_permissions) {
                $query->whereIn('id', $role_permissions); // Add this condition to filter out inactive pages
            }])->whereHas('pages', function ($query) use ($role_permissions) {
                $query->whereIn('id', $role_permissions);
            })->orderBy('sorting', 'asc')->get();

            }
            


            $view->with('modules', $modules);
        });

        // for header notification dots
        view()->composer('layout.include.header', function ($view) {
            $user = Auth::user() ;
            if($user){
                $hasHeaderDot = Friend::where('friend_id', $user->id)->where('header_notification_dot', 0)->exists();
                $view->with('hasHeaderDot', $hasHeaderDot);
            }
            
        });



    }

  
    

}
