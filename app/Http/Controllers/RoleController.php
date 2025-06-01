<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\AdminModule;
use App\Models\Role;
use App\Models\RolePermission;

class RoleController extends Controller
{   

  public function AddRole(Request $request){

    	$modules = AdminModule::with('pages')->get();
        // $pages = DB::table('admin_module_pages')->get();
        // dd($modules);
      return view('admin.roles.create-role')->with(compact('modules'));
  }
    
	public function SaveRole(Request $request)
	{
	    $validatedData = $request->validate([
	        'role' => 'required',
	        'admin_module_page_id.*' => 'integer',
	    ]);

	    $role = Role::create([
	        'role' => $request->role,
	    ]);

	    // dd($request->module_id);

	    // Loop through the selected checkboxes and create new role_permission records in the role_permissions table
		  if (!empty($request->admin_module_page_id)) {
            foreach ($request->admin_module_page_id as $key => $pageId) {
                $moduleId = $request->input("module_id.$pageId"); // get module ID from array using page ID as key
                $page_route = $request->input("page_route.$pageId");
                RolePermission::create([
                    'role_id' => $role->id,
                    'module_id' => $moduleId,
                    'admin_module_page_id' => $pageId,
                    'page_route' => $page_route
                ]);
            }
      }

	    return redirect()->back()->with('success', 'Role created successfully.');
	}

	public function ViewRole(){
	    return view('admin.roles.view-roles');
	}

	public function GetRole(Request $request){
        
      $totalFilteredRecord = $totalDataRecord = $draw_val = "";
      $columns_list = array(
          0 =>'id',
          1 =>'name',
          2 =>'status',
          3 =>'id',
      );
      $totalDataRecord = Role::where(['type'=>1])->count();
      $totalFilteredRecord = $totalDataRecord;

      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $order_val = $columns_list[$request->input('order.0.column')];
      $dir_val = $request->input('order.0.dir');
      // where(['type'=>1])->
      if(empty($request->input('search.value')))
      {
          $post_data = Role::where(['type'=>1])->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }else {
          $search_text = $request->input('search.value');
          $search2 =explode(' ',$search_text);
          $post_data = Role::where(['type'=>1])->select('*');
          if(!empty($search2)){
              foreach($search2 as $index=>$keywords){
                  if($keywords!=""){
                      
                      $post_data->where('name', 'LIKE', "%$keywords%")->orWhere('status', 'LIKE', "%$keywords%");

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
              $edit=route('admin.edit-role',$post_val->id);
              $delete=route('admin.delete-role',$post_val->id);
              $postnestedData['Sr'] = $sr=$sr+1;
              $postnestedData['Name'] = $post_val->role;
              // $postnestedData['Bio'] = $post_val->bio;
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
                          <h5 class="modal-title" id="exampleModalLabel">Delete Role</h5>
                          <button type="button" class="close" data-dismiss="modal"
                              aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          Are You Sure Want to Delete ?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary"
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

    public function RoleStatus(Request $request){

            if($request->val == 1){
            $val = 0;
            }
            else{
            $val = 1;
            }
            $Role = Role::where(['id'=>$request->id])->update(['status'=>$val]);
            if($Role){
            $array = array('value'=>$request->val,'id'=>$request->id);
            echo json_encode($array);
            }

    }

    public function DeleteRole(Request $request,$id){
            
            $Role = Role::where(['id'=>$id])->delete();
            $RolePermission = RolePermission::where(['role_id'=>$id])->delete();
            return redirect()->back()->with('success','Role Deleted Successfully'); 

    }

    public function EditRole(Request $request,$id){
            $roleId = $id; // replace with the actual role ID you want to query
            $role = DB::table('roles')->where('id', $roleId)->first();

            $savedPermissions = DB::table('role_permessions')
                        ->where('role_id', $roleId)
                        ->pluck('admin_module_page_id')
                        ->toArray();
        
            
            $modules = AdminModule::with('pages')->orderBy('sorting', 'asc')->get();
            return view('admin.roles.edit-roles')->with(compact('modules','savedPermissions','role'));
    }

    public function Updaterole(Request $request,$id){


            $role = Role::findOrFail($id);

            // Update the role name
            $role->role = $request->input('role');
            $role->save();

            // dd($request);
            // Delete all existing permissions for this role
            DB::table('role_permessions')->where('role_id', $id)->delete();
            
            if (!empty($request->admin_module_page_id)) {
                foreach ($request->admin_module_page_id as $key => $pageId) {
                    $moduleId = $request->input("module_id.$pageId"); // get module ID from array using page ID as key
                    $page_route = $request->input("page_route.$pageId");
                    RolePermission::create([
                        'role_id' => $role->id,
                        'module_id' => $moduleId,
                        'admin_module_page_id' => $pageId,
                        'page_route' => $page_route
                    ]);
                }
            }


            return redirect()->back()->with('success','Role updated successfully');

    }

}
