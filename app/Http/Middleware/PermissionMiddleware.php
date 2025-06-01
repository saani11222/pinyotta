<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Role;
use Str;
use App\Models\User;
use Session;
use App\Models\RolePermission;
use App\Models\AdminModulePage;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    { 


     $adminId = Session::get('adminid');
     $admin = Admin::where('admin_id', $adminId)->first();
     
     if($admin->type == "2"){
        return $next($request);
     }
     else{

        $role = Role::where('id', $admin->role_id)->first();

        // Get the requested page URL
        $pageUri = Str::replaceFirst(url('/'), '', request()->url());
        // Remove the dynamic "id" parameter from the URL

        $pageUrl = preg_replace('/\/\d+/', '', $pageUri);

        if($pageUrl == '/admin/edit-page'){
        $segments = explode('/', $pageUri);
        $id = last($segments);
        }
        
        $user = User::where(['id'=>@$id])->first();
    
        // if($user){
            
        //     if(@$user->type_of_user == 'community_owners'){

        //         // Check if the admin has permission to access the requested page
        //         $hasPermission = RolePermission::where('role_id', $role->id)
        //             ->where('page_route', $pageUrl)->where('module_id', 4)->exists();
            
        //         if (!$hasPermission) {
        //             // If the admin doesn't have permission, redirect them back
        //             return redirect()->back()->with('error', 'You do not have permission to access this page');
        //         }

        //         // If the admin has permission, allow them to access the requested page
        //         return $next($request);

        //     }
        //     elseif(@$user->type_of_user == 'platform_user'){

        //         // Check if the admin has permission to access the requested page
        //         $hasPermission = RolePermission::where('role_id', $role->id)
        //             ->where('page_route', $pageUrl)->where('module_id', 8)->exists();
            
        //         if (!$hasPermission) {
        //             // If the admin doesn't have permission, redirect them back
        //             return redirect()->back()->with('error', 'You do not have permission to access this page');
        //         }

        //         // If the admin has permission, allow them to access the requested page
        //         return $next($request);
            
        //     }
        //     elseif(@$user->type_of_user == 'community_peoples'){

        //         $hasPermission = RolePermission::where('role_id', $role->id)
        //             ->where('page_route', $pageUrl)->where('module_id', 5)->exists();
            
        //         if (!$hasPermission) {
        //             // If the admin doesn't have permission, redirect them back
        //             return redirect()->back()->with('error', 'You do not have permission to access this page');
        //         }

        //         // If the admin has permission, allow them to access the requested page
        //         return $next($request);
            
        //     }

        // }

        // dd($pageUrl);
        // Skip middleware for admin dashboard
        if ($pageUrl === '/admin/dashboard') {
            return $next($request);
        }
        // $adminModulePage = AdminModulePage::where('page_route', $pageUrl)->first();

        // Check if the admin has permission to access the requested page
        $hasPermission = RolePermission::where('role_id', $role->id)
            ->where('page_route', $pageUrl)->exists();

        if (!$hasPermission) {
            // If the admin doesn't have permission, redirect them back
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        // If the admin has permission, allow them to access the requested page
        return $next($request);

     }



    }



}
