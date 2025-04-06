<?php

namespace App\Http\Controllers;

use App\Models\RemovedShow;
use App\Models\Show;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        return view('index');
    }
    public function saveSession(Request $request) {
        $showsLoved = session('shows_loved', []);
        $showsLoved[] = [
            'item_id' => $request->item_id,
            'item_name' => $request->item_name,
            'item_image' => $request->item_image,
            'genres' => $request->genres,
        ];
    
        // Store updated array in session
        session(['shows_loved' => $showsLoved]);
        
        return response()->json(['message' => 'Session data stored successfully']);
        
    }
    
    public function signup(){
        return view('signup-page');
    }
    public function home(){

        $shows = session('shows_loved')[0] ?? null;
        $id = Auth::user()->id ?? null;

        // store session data into shows table
        if(!empty($shows) && $id){
            $user = Show::where('user_id', $id)->exists();
            if(!$user){
                foreach ($shows['item_id'] as $index => $itemId) {
                    $show = new Show;
                    $show->user_id = $id;
                    $show->show_id = $itemId;
                    $show->name = $shows['item_name'][$index];
                    $show->image = $shows['item_image'][$index];
                    $show->genres = $shows['genres'][$index];
                    $show->type = 'shows-love';
                    $show->save();
                }
                session()->forget('shows_loved');
            }else{
                session()->forget('shows_loved');
            }
        }

        // get the list of shows 
        if($id){
            $currentUserShows = DB::table('shows')
                ->where('user_id', $id)
                ->where('type', 'shows-love')
                ->pluck('show_id')
            ->toArray();
            // get the matched users
            $similarUsers = DB::table('shows as s1')
                ->join('shows as s2', function ($join) use ($id) {
                    $join->on('s1.show_id', '=', 's2.show_id')
                        ->where('s1.user_id', '=', $id)
                        ->where('s2.user_id', '!=', $id);
                })
                ->where('s1.type', 'shows-love')
                ->where('s2.type', 'shows-love')
                ->select('s2.user_id',
                    DB::raw('ROUND((COUNT(s1.show_id) * 2 / ((SELECT COUNT(DISTINCT show_id) FROM shows WHERE user_id = '.$id.' AND type = "shows-love") +
                            (SELECT COUNT(DISTINCT show_id) FROM shows WHERE user_id = s2.user_id AND type = "shows-love")) * 100), 0) as percentage'),
                            DB::raw('GROUP_CONCAT(s2.show_id) as matched_show_ids'))
                ->groupBy('s2.user_id')
                ->having('percentage', '>=', 70)
            ->get();
            
            $recommendations = null;
            // get the recommendations
            if($similarUsers->isNotEmpty()){

                $similarInterestUser = $similarUsers->pluck('user_id')->toArray();
                $matched_show_ids = $similarUsers->pluck('matched_show_ids')->toArray();
                $matched_show_ids = explode(',',$matched_show_ids[0]) ?? null;
                $removed_shows = RemovedShow::where(['user_id'=> $id , 'type' => 'shows-love'])
                ->pluck('show_id')->toArray();
                // dd($removed_shows);
                $recommendations = DB::table('shows')
                    ->select(
                        'show_id',
                        DB::raw('MAX(user_id) as user_id'), 
                        DB::raw('MAX(name) as name'),
                        DB::raw('MAX(image) as image'),
                        DB::raw('MAX(genres) as genres')
                    )
                ->whereIn('user_id', $similarInterestUser)
                ->whereNotIn('show_id', $matched_show_ids)
                ->whereNotIn('show_id', $removed_shows)
                ->where('type', 'shows-love')
                ->groupBy('show_id')
                ->get();
            }  
            return view('home' , compact('recommendations'));

        }else{
            return redirect()->route('index');
        }
    }
    public function friends(){

        $friends = null;
        $userId = Auth::user()->id ?? null;
        $latestShows= null;
        if($userId){
               $user = User::with('friends.shows')->find($userId);
            if($user->friends->isNotEmpty()){
                $friends = $user->friends;
                // get friends shows i love
                $friendsId = $user->friends->pluck('id')->toArray();
                $latestShows = Show::whereIn('user_id', $friendsId)
                ->where('type', 'shows-love')
                ->orderByDesc('updated_at')
                ->get()->groupBy('user_id')
                ->map(function ($shows) {
                    return $shows->first();
                });
                $friendsWithShows = $friends->map(function ($friend) use ($latestShows) {
                    $friend->latest_show = $latestShows[$friend->id] ?? null;
                    return $friend;
                });
            }
        }

        return view('friends' , compact('friends' ));
    }

    public function watchList(){
        return view('watch-list');
    }
    public function showsLoved(){
        // $showsList = Show::where('user_id', Auth::user()->id)->get();
        // dd($showsList);
        return view('show-i-love');
    }
    public function account($id){
        
        return view('model.account');
    }
    public function friendsShowsList($id){
        $removedShowIds = RemovedShow::where('user_id', $id)
                    ->where('type', 'shows-love')
                    ->pluck('show_id')
                    ->toArray();
        $shows = Show::where('user_id', $id)
                    ->where('type', 'shows-love')
                    ->whereNotIn('show_id', $removedShowIds)
                    ->get();
        $user = User::select('name')->find($id);
        
        return view('model.shows-friends-love', compact('shows','user'));
    }
    public function addFriend(){
        return view('model.add-friend');
    }
    public function addShow(){
        return view('model.add-show');
    }
    public function howToWork(){
        return view('how-it-work');
    }
    public function signIn(){
        return view('signin-page');
    }

    public function googleAuth(){
        $googleUser = Socialite::driver('google')->user();
        
        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            // 'avatar' => $googleUser->getAvatar(),
        ]);
    
        Auth::login($user);
    
        return redirect('/home');
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    
    
}


