<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessUserCsvRow;
use App\Models\Show;
use App\Models\Friend;
use App\Models\FriendInvite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\RemovedShow;
use App\Models\Restaurant;

class UserController extends Controller
{
    
  public function ViewUsers(Request $request){

      return view('admin.users.view-users');

  }

  public function GetUsers(Request $request){
        
      $totalFilteredRecord = $totalDataRecord = $draw_val = "";
      $columns_list = array(
          0 =>'id',
          1 =>'name',
          2 =>'status',
          3 =>'id',
      );
      $totalDataRecord = User::where(['type'=>'1'])->count();
      $totalFilteredRecord = $totalDataRecord;

      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $order_val = $columns_list[$request->input('order.0.column')];
      $dir_val = $request->input('order.0.dir');
      // where(['type'=>1])->
      if(empty($request->input('search.value')))
      {
          $post_data = User::where(['type'=>'1'])->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }else {
          $search_text = $request->input('search.value');
          $search2 =explode(' ',$search_text);
          $post_data = User::where(['type'=>'1'])->select('*');
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
              $friends=route('admin.view-user-friends',$post_val->id);
              $delete=route('admin.delete-user',$post_val->id);
              $watchlist = route('admin.view-user-shows', ['id' => $post_val->id, 'type' => 'watchlist']);
              $showslove= route('admin.view-user-shows', ['id' => $post_val->id, 'type' => 'shows-love']);
              $restaurants= route('admin.user-restaurants', ['id' => $post_val->id]);

              $postnestedData['Sr'] = $sr=$sr+1;
              $postnestedData['Name'] = $post_val->name;
              $postnestedData['Email'] = $post_val->email;

                $action = '
				<div class="dropdown">
				  <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="actionMenu'.$post_val->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Shows
				  </button>
				  <div class="dropdown-menu" aria-labelledby="actionMenu'.$post_val->id.'">
				    <a class="dropdown-item" href="'.$watchlist.'"><i class="fa fa-eye"></i> Watchlist</a>
				    <a class="dropdown-item" href="'.$showslove.'"><i class="fa fa-star"></i> Shows I Love</a>
            <a class="dropdown-item" href="'.$friends.'"><i class="fa fa-user-friends"></i> Friends</a>
            <a class="dropdown-item" href="'.$restaurants.'"><i class="fa fa-utensils"></i> Restaurants</a>
            <a class="dropdown-item" data-toggle="modal" data-target="#exampleModal'.$post_val->id.'" href="#"><i class="fa fa-trash"></i> Remove</a>
				  </div>
				</div>';

        $action .='<div class="modal fade" id="exampleModal'.$post_val->id.'" tabindex="-1"
              role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Remove User</h5>
                          <button type="button" class="close" data-dismiss="modal"
                              aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          Are You Sure Want to Delete ?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-success"
                              data-dismiss="modal">Close</button>
                          <a href="'.$delete.'"
                              class="btn btn-danger">Delete</a>
                      </div>
                  </div>
              </div>
          </div>';

				$postnestedData['Actions'] = $action;


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

  public function DeleteUser(Request $request, $id){

    Show::where(['user_id'=>$id])->delete();
    Friend::where(['user_id'=>$id])->delete();
    Friend::where(['friend_id'=>$id])->delete();
    FriendInvite::where('user_id',$id)->delete();
    RemovedShow::where('user_id',$id)->delete();
    User::where(['id'=>$id])->delete();

    return redirect()->back()->with('success','User Deleted Successfully'); 
    
  }

  public function UserRestaurants(Request $request, $id){   

      return view('admin.restaurants.view-user-restaurants', compact('id'));

  }

  public function GetUserRestaurants(Request $request){
        
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
        $columns_list = array(
          0 =>'id',
          1 =>'name',
          3 =>'id',
        );
        
        $userId = $request->input('user_id');
        $query = Restaurant::where('user_id', $userId);


      $totalDataRecord = $query->count();
      $totalFilteredRecord = $totalDataRecord;

      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $order_val = $columns_list[$request->input('order.0.column')];
      $dir_val = $request->input('order.0.dir');
      // where(['type'=>1])->
      if(empty($request->input('search.value')))
      {
          $post_data = Restaurant::where(['user_id'=>$userId])->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }else {
          $search_text = $request->input('search.value');
          $search2 =explode(' ',$search_text);
          $post_data = Restaurant::where(['user_id'=>$userId])->select('*');
          if(!empty($search2)){
              foreach($search2 as $index=>$keywords){
                  if($keywords!=""){
                      
                      $post_data->where('name', 'LIKE', "%$keywords%")->orWhere('city', 'LIKE', "%$search_text%");

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
        
              $postnestedData['Sr'] = $sr=$sr+1;
              $postnestedData['Name'] = $post_val->name;
              $postnestedData['City'] = $post_val->city;
              // $postnestedData['Image'] = '<img src="' . post_val->image . '" alt="Image" width="60" height="60" style="object-fit:cover; border-radius:4px;">';
              
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

	public function ViewUserShows(Request $request, $id, $type){   

	    return view('admin.shows.view-user-shows', compact('id', 'type'));

	}

	public function GetUserShows(Request $request){
        
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";
        $columns_list = array(
          0 =>'id',
          1 =>'name',
          3 =>'id',
        );
        
        $userId = $request->input('user_id');
	    $type = $request->input('type');
        $query = Show::where('user_id', $userId);

	    if ($type === 'watchlist') {
	        $query->where('type', 'watchlist');
	    } elseif ($type === 'shows-love') {
	        $query->where('type', 'love');
	    }

	    $totalDataRecord = $query->count();
	    $totalFilteredRecord = $totalDataRecord;

      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $order_val = $columns_list[$request->input('order.0.column')];
      $dir_val = $request->input('order.0.dir');
      // where(['type'=>1])->
      if(empty($request->input('search.value')))
      {
          $post_data = Show::where(['user_id'=>$userId,'type'=>$type])->offset($start_val)
          ->limit($limit_val)
          ->orderBy($order_val,$dir_val)
          ->get();
      }else {
          $search_text = $request->input('search.value');
          $search2 =explode(' ',$search_text);
          $post_data = Show::where(['user_id'=>$userId,'type'=>$type])->select('*');
          if(!empty($search2)){
              foreach($search2 as $index=>$keywords){
                  if($keywords!=""){
                      
                      $post_data->where('name', 'LIKE', "%$keywords%")->orWhere('genres', 'LIKE', "%$search_text%");

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
        
              $postnestedData['Sr'] = $sr=$sr+1;
              $postnestedData['Name'] = $post_val->name;
              $postnestedData['Genres'] = $post_val->genres;
              $postnestedData['Image'] = '<img src="' . $post_val->image . '" alt="Image" width="60" height="60" style="object-fit:cover; border-radius:4px;">';
              
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

  public function ViewUserFriends(Request $request, $id){

    return view('admin.friends.view-user-friends', compact('id'));

  }

  public function GetUserFriends(Request $request){
    
      $userId = $request->input('user_id');
      $draw_val = $request->input('draw');
      $limit_val = $request->input('length');
      $start_val = $request->input('start');
      $search_text = $request->input('search.value');
      $order_val = $request->input('order.0.column');
      $dir_val = $request->input('order.0.dir');

      $columns_list = [
          0 => 'id',
          1 => 'name',
          2 => 'email',
      ];

      $user = User::find($userId);

      if (!$user) {
          return response()->json([
              "draw" => intval($draw_val),
              "recordsTotal" => 0,
              "recordsFiltered" => 0,
              "data" => []
          ]);
      }

      // Get friends relation (pivot)
      $friendsQuery = $user->friends();

      if (!empty($search_text)) {
          $searchWords = explode(' ', $search_text);
          $friendsQuery->where(function ($q) use ($searchWords) {
              foreach ($searchWords as $word) {
                  $q->orWhere('name', 'like', "%{$word}%")
                    ->orWhere('email', 'like', "%{$word}%");
              }
          });
      }

      $totalDataRecord = $user->friends()->count();
      $totalFilteredRecord = $friendsQuery->count();

      $friends = $friendsQuery
          ->offset($start_val)
          ->limit($limit_val)
          ->orderBy($columns_list[$order_val], $dir_val)
          ->get();

      $data_val = [];
      $sr = $start_val;

      foreach ($friends as $friend) {
          $data_val[] = [
              'Sr' => ++$sr,
              'Name' => $friend->name,
              'Email' => $friend->email,
          ];
      }

      return response()->json([
          "draw" => intval($draw_val),
          "recordsTotal" => intval($totalDataRecord),
          "recordsFiltered" => intval($totalFilteredRecord),
          "data" => $data_val
      ]);

  }

  public function UploadUsercsv(Request $request){

      $file = $request->file('csv');
      $customerArrs = $this->csvToArray($file);
      // dd($customerArrs);
      foreach ($customerArrs as $customerArr)
      {
           // $this->TestFucntion($customerArr);
           $user = User::where('email', $customerArr['email'])->first();
           if(!$user){
            ProcessUserCsvRow::dispatch($customerArr);
           }
      }

      return redirect()->back()->with('success','Record Saved Successfully'); 
  }

  public function TestFucntion($data){

      $userData = $data;
      $user = User::where(['email'=>$userData['email']])->first();
      if($user){

      }
      else{
        
        $user = new User;
        $email = $userData['email'];
        $name = strstr($email, '@', true);
        $user->email = $email;
        $user->name = $name;
        $user->type = '0';
        $user->save();

        $imdbIds = explode(',', $userData['imdb_ids']);

        foreach ($imdbIds as $imdbId) {
            $imdbId = trim($imdbId);

            $response = Http::get("https://api.tvmaze.com/lookup/shows", [
                'imdb' => $imdbId
            ]);

            if ($response->ok()) {
                $showData = $response->json();
                $show = new Show;

                $show->show_id = $showData['id'];
                $show->user_id = $user->id;
                $show->name = $showData['name'];

                $genres = isset($showData['genres']) ? implode(', ', $showData['genres']) : null;
                $year = isset($showData['premiered']) ? date('Y', strtotime($showData['premiered'])) : null;
                $show->genres = $year && $genres ? $year . ', ' . $genres : null;

                $image = isset($showData['image']['medium']) ? $showData['image']['medium'] : null;
                $show->image = $image;

                $show->type = 'shows-love';
                $show->save();


            } else {
                
            }
        }

      }

  }

  // function csvToArray($csvfile = '', $delimiter = ',') {

  //       $csv = Array();
  //       $rowcount = 0;
  //       if (($handle = fopen($csvfile, "r")) !== FALSE) {
  //           $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
  //           $header = fgetcsv($handle, $max_line_length);
  //           $header_colcount = count($header);
  //           while (($row = fgetcsv($handle, $max_line_length)) !== FALSE) {
  //               $row_colcount = count($row);
  //               if ($row_colcount == $header_colcount) {
  //                   $entry = array_combine($header, $row);
  //                   $csv[] = $entry;
  //               }
  //               else {
  //                   error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
  //                   return null;
  //               }
  //               $rowcount++;
  //           }
  //           //echo "Totally $rowcount rows found\n";
  //           fclose($handle);
  //       }
  //       else {
  //           error_log("csvreader: Could not read CSV \"$csvfile\"");
  //           return null;
  //       }
  //       return $csv;

  // }

  // function csvToArray($csvfile = '', $delimiter = ',') {

  //     $csv = [];
  //     $rowcount = 0;

  //     if (($handle = fopen($csvfile, "r")) !== FALSE) {
  //         $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
  //         $header = fgetcsv($handle, $max_line_length);

  //         // 🔧 Remove BOM from first header item (usually from Excel/Google Sheets)
  //         if (!empty($header)) {
  //             $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
  //         }

  //         $header_colcount = count($header);

  //         while (($row = fgetcsv($handle, $max_line_length)) !== FALSE) {
  //             $row_colcount = count($row);
  //             if ($row_colcount == $header_colcount) {
  //                 $entry = array_combine($header, $row);
  //                 $csv[] = $entry;
  //             } else {
  //                 error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
  //                 return null;
  //             }
  //             $rowcount++;
  //         }

  //         fclose($handle);
  //     } else {
  //         error_log("csvreader: Could not read CSV \"$csvfile\"");
  //         return null;
  //     }

  //     return $csv;
      
  // }

  function csvToArray($csvfile = '', $delimiter = ',') {

      $csv = [];
      $rowcount = 0;

      if (($handle = fopen($csvfile, "r")) !== FALSE) {
          $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
          $header = fgetcsv($handle, $max_line_length, $delimiter);

          // 🔧 Remove BOM from first header item (Excel/Google Sheets)
          if (!empty($header)) {
              $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
          }

          $header_colcount = count($header);

          while (($row = fgetcsv($handle, $max_line_length, $delimiter)) !== FALSE) {
              // ✅ Skip completely empty rows
              if (count(array_filter($row)) === 0) {
                  continue;
              }

              $row_colcount = count($row);

              if ($row_colcount == $header_colcount) {
                  $entry = array_combine($header, $row);
                  $csv[] = $entry;
              } else {
                  error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
                  return null;
              }

              $rowcount++;
          }

          fclose($handle);
      } else {
          error_log("csvreader: Could not read CSV \"$csvfile\"");
          return null;
      }

      return $csv;
  }



}
