<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\AdminModule;
use Auth;
use Str;

class AdminController extends Controller
{   

    public function Admin(){

      return view('admin.login');
    }
  
    public function AdminLogin(Request $request){

        $request->validate([
        'email'=>'required|email',
        'password'=>'required|min:8',
        ]);

        $count = Admin::where(['email'=>$request->email,'status'=>1])->count();
           
           if($count){
            $getemail = Admin::where(['email'=>$request->email,'status'=>1])->first();
            // dd($getemail);
            $admin_id = $getemail['admin_id'];
        
            if (Hash::check($request->password ,$getemail->password)){
                Session::put(['adminid'=>$admin_id]);
                return redirect('/admin/dashboard');
            }          
        else{
            return redirect()->back()->with('error','Your password is wrong please try again');
            }

          }
        else{
        return redirect()->back()->with('error','your password or email wrong please try again !');
        }

    }

    public function AdminDashboard(){
        
        $admin_id = Session::get('adminid');
        $admin = Admin::where('admin_id', $admin_id)->first();
        if($admin->type=='2'){
        $data = "yes";
        }
        else{
        $dashboard_module = AdminModule::where('name', 'Dashboard')->first();

        if ($dashboard_module) {
            $dashboard_permissions = RolePermission::where('role_id', $admin->role_id)
                ->where('module_id', $dashboard_module->id)
                ->exists();
            // dd($dashboard_permissions);
            if ($dashboard_permissions) {
                // User has permission for the dashboard module, get all modules
                $modules = AdminModule::with(['pages' => function ($query) use ($admin) {
                    $query->whereIn('id', $admin->rolePermissions->pluck('admin_module_page_id'));
                }])->where('name', '<>', 'Dashboard')->get();

                $data = "yes";
            } else {
                // User does not have permission for the dashboard module, show an error or redirect to another page
                $data = "no";
            }
        } else {
            // Dashboard module not found, show an error or redirect to another page
            $data = "no";
        }

        }
        
        // $routeUri = Str::replaceFirst(url('/'), '', request()->url());
        // dd($routeUri);
        // dd("A");
        return view('admin.dashboard')->with(compact('data'));
    }

    public function AdminLogout(){
    
        Session::forget('adminid');
        return redirect('/admin')->with('success', 'You Logout Successfully');
    }

    public function CreateAdmin(){
        $roles = Role::where(['type'=>1])->get();
        return view('admin.admin_users.create-admin')->with(compact('roles'));
    }

    public function SaveAdmin(Request $request){
        
        $validatedData = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|min:8',
        'role_id' => 'required|integer',
        ]);

        $admin = new Admin;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->role_id = $request->role_id;
        $admin->admin_id = Str::random(20); // generate a random string for admin_id
        $admin->save();

        return redirect()->back()->with('success', 'Admin created successfully!');

    }

    public function ViewAdmin(Request $request){

        return view('admin.admin_users.view-admin');

    }

    public function GetAdmin(Request $request){

        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
      $columns_list = array(
          0 =>'id',
          1 =>'name',
          2 =>'email',
          3 =>'role',
          4 =>'status',
          5 =>'id',
      );
      $totalDataRecord = Admin::where(['type'=>1])->count();
      $totalFilteredRecord = $totalDataRecord;

      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $order_val = $columns_list[$request->input('order.0.column')];
      $dir_val = $request->input('order.0.dir');
      if(empty($request->input('search.value')))
      {
          $post_data = Admin::where(['type'=>1])->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }else {
          $search_text = $request->input('search.value');
          $search2 =explode(' ',$search_text);
          $post_data = Admin::where(['type'=>1])->select('*');
          if(!empty($search2)){
              foreach($search2 as $index=>$keywords){
                  if($keywords!=""){
                      
                      $post_data->where('name', 'LIKE', "%$keywords%")->orWhere('email', 'LIKE', "%$keywords%");

                  }
          
              }

          }
          $post_data=$post_data->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }
      $data_val = array();
      if(!empty($post_data)){
          $i=0;
          $sr=0;
          foreach ($post_data as $post_val)
          {
              $edit=route('admin.edit-admin',$post_val->id);
              $delete=route('admin.delete-admin',$post_val->id);
              $postnestedData['Sr'] = $sr=$sr+1;
              $postnestedData['Name'] = $post_val->name;
              $postnestedData['Email'] = $post_val->email;
              @$role_name = Role::find($post_val->role_id)->role;
              $postnestedData['Role'] = @$role_name;
              if($post_val->status == 1){
                    $postnestedData['Status'] = '<button type="button" class="btn btn-success btn-sm"
                    id="option'.$post_val->id.'"
                    data-val="'.$post_val->status.'" autocomplete="off"
                    onclick="block('.$post_val->id.')">Active</button>';
              }else{
                    $postnestedData['Status'] = '<button type="button" class="btn btn-danger btn-sm"
                    id="option'.$post_val->id.'"
                    data-val="'.$post_val->status.'" autocomplete="off"
                    onclick="block('.$post_val->id.')">Inactive</button>';
              }

              $action='';
              $action = '<button data-toggle="modal" data-target="#exampleModal'.$post_val->id.'"
              class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>
              Delete</button>';
              $action .= ' <a href="'.$edit.'"
              class="btn btn-primary btn-sm"></i><i class="fa fa-edit "></i> Edit</a>';
              
              $action .='<div class="modal fade" id="exampleModal'.$post_val->id.'" tabindex="-1"
              role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Delete Admin User</h5>
                          <button type="button" class="close" data-dismiss="modal"
                              aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          Are You Sure Want to Delete ?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-primary"
                              data-dismiss="modal">Close</button>
                          <a href="'.$delete.'"
                              class="btn btn-danger">Delete</a>
                      </div>
                  </div>
              </div>
          </div>';
              $postnestedData['Action'] = $action;

              $data_val[] = $postnestedData;

          }
      }

      $draw_val = $request->input('draw');
      $get_json_data = array(
          "draw"            => intval($draw_val),
          "recordsTotal"    => intval($totalDataRecord),
          "recordsFiltered" => intval($totalFilteredRecord),
          "data"            => $data_val
      );
      echo json_encode($get_json_data);

    }

    public function AdminStatus(Request $request){

        if($request->val == 1){
         $val = 0;
        }
        else{
         $val = 1;
        }
        $Admin = Admin::where(['id'=>$request->id])->update(['status'=>$val]);
        if($Admin){
         $array = array('value'=>$request->val,'id'=>$request->id);
         echo json_encode($array);
        }

    }

    public function DeleteAdmin(Request $request,$id){
        
        $Admin = Admin::where(['id'=>$id])->delete();
        return redirect()->back()->with('success','Admin Deleted Successfully'); 

    }

    public function EditAdmin(Request $request,$id){
        $admin = Admin::where(['id'=>$id,'status'=>1])->first();
        $roles = Role::where(['type'=>1])->get();
        return view('admin.admin_users.edit-admin')->with(compact('admin','roles'));
        // dd("hello");
    }

    public function UpdateAdmin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->role_id = $request->input('role_id');

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->back()->with('success', 'Admin updated successfully.');
    }

}
