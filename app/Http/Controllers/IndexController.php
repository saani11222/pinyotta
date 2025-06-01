<?php

namespace App\Http\Controllers;

use App\Models\RecommendationLog;
use App\Models\RemovedShow;
use App\Models\RestaurantsAi;
use App\Models\Show;
use App\Models\AiShows;
use App\Models\User;
use App\Models\FriendInvite;
use App\Models\WatchList;
use App\Models\Friend;
use App\Models\SaveBookmark;
use App\Models\Restaurant;
use App\Models\RestaurantLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    function moveToBookMarks($case, $case_id, $from)
    {
        if ($case == 'tvshow') {
            $show_id = $case_id;
            $user_id = auth()->id();
            $exists = SaveBookmark::where('user_id', $user_id)->where('show_id', $show_id)->exists();
            if ($exists) {
                return response()->json([
                    'error' => true,
                    'message' => 'Already Exist'
                ], 409);
            } else {
                if ($from == 'ai_recs') {
                    $show = AiShows::where(['show_id' => $show_id, 'user_id' => $user_id])->first();
                }
                if ($from == 'friends') {
                    $show = Show::where('show_id', $show_id)->first();;
                }
                $bookmark = new  SaveBookmark;
                $bookmark->user_id = $user_id;
                $bookmark->show_id = $show->show_id;
                $bookmark->name = $show->name;
                $bookmark->genres = $show->genres;
                $bookmark->image = $show->image;
                $bookmark->type = 'bookmark-show';
                $bookmark->summary = $show->summary;

                $bookmark->save();
                if ($from == 'ai_recs') {
                    $show->delete();
                }
            }
            return response()->json(['message' => 'successfully updated']);
        }
    }
    public function index(Request $request)
    {
        // Session::forget('fid_list');
        if ($request->has('fid')) {
            $current_fid = $request->query('fid');
            $match_refrel_id = FriendInvite::where('random_token', $current_fid)->first();
            if ($match_refrel_id) {
                $fidList = Session::get('fid_list', []);
                if (!in_array($current_fid, $fidList)) {
                    $fidList[] = $current_fid;
                    Session::put('fid_list', $fidList);
                }
            } else {
                return view('link-expired');
            }
        }
        return view('index');
    }


    public function saveSession(Request $request)
    {
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

    public function signup()
    {
        //   $restaurantsLoved = session('restaurants_loved');
        // dd($restaurantsLoved);
        return view('signup-page');
    }
    public function home(Request $request)
    {


        $id = Auth::user()->id ?? null;
        if ($id) {

            // $id  = 64;
            // Step 1: Get all show_ids loved by current user
            // $currentUserShowIds = DB::table('shows')
            //     ->where('user_id', $id)
            //     ->where('type', 'shows-love')
            //     ->pluck('show_id')
            //     ->toArray();

            // //   dd($currentUserShowIds);
            // if (empty($currentUserShowIds)) {
            //     $similarUsers = collect();
            // }else{
            //     $currentUserLoveCount = count($currentUserShowIds);

            //     // Step 2: Fetch other users who loved these shows
            //     $otherUsers = DB::table('shows')
            //     ->select('user_id', DB::raw('GROUP_CONCAT(show_id) as matched_show_ids'), DB::raw('COUNT(DISTINCT show_id) as common_count'))
            //     ->where('type', 'shows-love')
            //     ->whereIn('show_id', $currentUserShowIds)
            //     ->where('user_id', '!=', $id)
            //     ->groupBy('user_id')
            //     ->limit(100)
            //     ->get();

            //     // Get all other user IDs
            //     $otherUserIds = $otherUsers->pluck('user_id')->toArray();

            //     // Step 3: Fetch their total loved shows (VERY important)
            //     $otherUsersLoveCounts = DB::table('shows')
            //     ->select('user_id', DB::raw('COUNT(DISTINCT show_id) as love_count'))
            //     ->where('type', 'shows-love')
            //     ->whereIn('user_id', $otherUserIds)
            //     ->groupBy('user_id')
            //     ->pluck('love_count', 'user_id');

            //     // Step 4: Now calculate percentage properly
            //     $similarUsers = $otherUsers->map(function ($user) use ($currentUserLoveCount, $otherUsersLoveCounts) {
            //     $matchedShowIds = explode(',', $user->matched_show_ids);
            //     $commonCount = $user->common_count;
            //     $otherUserLoveCount = $otherUsersLoveCounts[$user->user_id] ?? 1;

            //     $percentage = round(($commonCount * 2.0 / ($currentUserLoveCount + $otherUserLoveCount)) * 100, 0);

            //     return [
            //         'user_id' => $user->user_id,
            //         'percentage' => $percentage,
            //         'matched_show_ids' => $matchedShowIds,
            //     ];
            //     })->filter(function ($user) {
            //     return $user['percentage'] >= 70;
            //     })->take(20);



            // }

















            // $similarUsers = DB::select("
            // WITH current_user_shows AS (
            //     SELECT show_id
            //     FROM shows
            //     WHERE type = 'shows-love'
            //       AND user_id = ?
            //     LIMIT 500  -- Limit the number of shows considered for the current user
            // ),
            // user_matches AS (
            //     SELECT 
            //         s.user_id,
            //         COUNT(DISTINCT s.show_id) as common_shows,
            //         GROUP_CONCAT(DISTINCT s.show_id ORDER BY s.show_id) as matched_show_ids  -- Including matched show IDs
            //     FROM shows s
            //     INNER JOIN current_user_shows cus ON s.show_id = cus.show_id
            //     WHERE s.type = 'shows-love'
            //       AND s.user_id != ?
            //     GROUP BY s.user_id
            // )
            // SELECT 
            //     um.user_id,
            //     um.matched_show_ids,
            //     ROUND((um.common_shows * 2.0 / (u.shows_count + (SELECT COUNT(*) FROM current_user_shows))) * 100, 0) AS match_percentage
            // FROM user_matches um
            // JOIN users u ON u.id = um.user_id
            // WHERE ROUND((um.common_shows * 2.0 / (u.shows_count + (SELECT COUNT(*) FROM current_user_shows))) * 100, 0) >= 70
            // ORDER BY match_percentage DESC
            // LIMIT 10
            // ", [$id, $id]);





            //             $similarUsers = DB::select("
            //     WITH current_user_shows AS (
            //         -- Select shows for the current user
            //         SELECT show_id
            //         FROM shows
            //         WHERE user_id = ? AND type = 'shows-love'
            //         -- Consider removing or adjusting LIMIT 500 for accuracy
            //         -- LIMIT 500
            //     ),
            //     current_user_show_count AS (
            //         -- Calculate the count of the current user's shows ONCE
            //         SELECT COUNT(*) as ct
            //         FROM current_user_shows
            //     ),
            //     user_matches AS (
            //         -- Find other users who loved the same shows
            //         SELECT
            //             s.user_id,
            //             COUNT(DISTINCT s.show_id) as common_shows,
            //             GROUP_CONCAT(DISTINCT s.show_id ORDER BY s.show_id) as matched_show_ids
            //         FROM shows s
            //         -- Join based on the shows the current user loves
            //         INNER JOIN current_user_shows cus ON s.show_id = cus.show_id
            //         WHERE s.user_id != ? AND s.type = 'shows-love' -- Filter for other users loving the same shows
            //         GROUP BY s.user_id
            //     ),
            //     ranked_matches AS (
            //         -- Calculate the match percentage efficiently
            //         SELECT
            //             um.user_id,
            //             um.matched_show_ids,
            //             um.common_shows,
            //             u.shows_count, -- Needed for calculation
            //             cusc.ct as current_user_count, -- Get the pre-calculated count
            //             -- Calculate percentage using pre-calculated count
            //             ROUND((um.common_shows * 2.0 / (u.shows_count + cusc.ct)) * 100, 0) AS match_percentage
            //         FROM user_matches um
            //         JOIN users u ON u.id = um.user_id
            //         -- Cross join is safe here as current_user_show_count has only one row
            //         CROSS JOIN current_user_show_count cusc
            //         -- Pre-filter based on common_shows if possible (optional optimization)
            //         -- This requires knowing something about the distribution of shows_count
            //         -- For example, if you know max(shows_count) or avg(shows_count)
            //         -- WHERE um.common_shows > some_threshold -- Might prune some results early
            //     )
            //     -- Final selection and filtering
            //     SELECT
            //         user_id,
            //         matched_show_ids,
            //         match_percentage
            //     FROM ranked_matches
            //     WHERE match_percentage >= 70
            //     ORDER BY match_percentage DESC
            //     LIMIT 10
            // ", [$id, $id]); // Pass $id twice: once for current_user_shows, once for user_matches filter




            // Define a cache key based on the user ID
            $cacheKey = "similar_users_{$id}_matches";
            // Attempt to get the cached result, or run the query if not cached
            // $similarUsers = Cache::remember($cacheKey, 600, function () use ($id) {
            //     return DB::select("
            //     WITH current_user_shows AS (
            //         SELECT show_id
            //         FROM shows
            //         WHERE user_id = $id AND type = 'shows-love'
            //     ),
            //     current_user_show_count AS (
            //         SELECT COUNT(*) as ct
            //         FROM current_user_shows
            //     ),
            //     user_matches AS (
            //         SELECT
            //             s.user_id,
            //             COUNT(DISTINCT s.show_id) as common_shows,
            //             GROUP_CONCAT(DISTINCT s.show_id ORDER BY s.show_id) as matched_show_ids
            //         FROM shows s
            //         INNER JOIN current_user_shows cus ON s.show_id = cus.show_id
            //         WHERE s.user_id != $id AND s.type = 'shows-love'
            //         GROUP BY s.user_id
            //         HAVING COUNT(DISTINCT s.show_id) >= 4

            //     ),
            //     ranked_matches AS (
            //         SELECT
            //             um.user_id,
            //             um.matched_show_ids,
            //             um.common_shows,
            //             u.shows_count,
            //             cusc.ct as current_user_count,
            //             ROUND((um.common_shows * 2.0 / (u.shows_count + cusc.ct)) * 100, 0) AS match_percentage
            //         FROM user_matches um
            //         JOIN users u ON u.id = um.user_id
            //         CROSS JOIN current_user_show_count cusc
            //     )
            //     SELECT
            //         user_id,
            //         matched_show_ids,
            //         match_percentage
            //     FROM ranked_matches
            //     WHERE match_percentage >= 70
            //     ORDER BY match_percentage DESC
            //     LIMIT 50");
            // });

            // dd($similarUsers);

            // You will get $results as an array of objects





            //             $sql = "
            //     WITH love_counts AS (
            //         SELECT user_id, COUNT(DISTINCT show_id) as love_count
            //         FROM shows
            //         WHERE type = 'shows-love'
            //         GROUP BY user_id
            //     ),
            //     similar_loves AS (
            //         SELECT 
            //             s2.user_id,
            //             COUNT(s1.show_id) as common_count,
            //             lc1.love_count as current_user_love_count,
            //             lc2.love_count as other_user_love_count,
            //             GROUP_CONCAT(s2.show_id) as matched_show_ids
            //         FROM shows s1
            //         JOIN shows s2 ON s1.show_id = s2.show_id AND s2.user_id != s1.user_id
            //         JOIN love_counts lc1 ON lc1.user_id = s1.user_id
            //         JOIN love_counts lc2 ON lc2.user_id = s2.user_id
            //         WHERE s1.user_id = :id
            //           AND s1.type = 'shows-love'
            //           AND s2.type = 'shows-love'
            //         GROUP BY s2.user_id, lc1.love_count, lc2.love_count
            //     )
            //     SELECT 
            //         user_id,
            //         ROUND((common_count * 2.0 / (current_user_love_count + other_user_love_count)) * 100, 0) as percentage,
            //         matched_show_ids
            //     FROM similar_loves
            //     WHERE ROUND((common_count * 2.0 / (current_user_love_count + other_user_love_count)) * 100, 0) >= 70
            //     LIMIT 200
            // ";

            // $similarUsers = DB::select($sql, ['id' => $id]);
            $similarUsers = [];
            $similarUsers = collect($similarUsers);
            // dd($similarUsers);

            // $similarUsers = collect();




            // get the matched users
            // $similarUsers = DB::table('shows as s1')
            //     ->join('shows as s2', function ($join) use ($id) {
            //         $join->on('s1.show_id', '=', 's2.show_id')
            //             ->where('s1.user_id', '=', $id)
            //             ->where('s2.user_id', '!=', $id);
            //     })
            //     ->where('s1.type', 'shows-love')
            //     ->where('s2.type', 'shows-love')
            //     ->select('s2.user_id',
            //         DB::raw('ROUND((COUNT(s1.show_id) * 2 / ((SELECT COUNT(DISTINCT show_id) FROM shows WHERE user_id = '.$id.' AND type = "shows-love") +
            //                 (SELECT COUNT(DISTINCT show_id) FROM shows WHERE user_id = s2.user_id AND type = "shows-love")) * 100), 0) as percentage'),
            //                 DB::raw('GROUP_CONCAT(s2.show_id) as matched_show_ids'))
            //     ->groupBy('s2.user_id')
            //     ->having('percentage', '>=', 70)
            //     ->limit(20)
            //     ->get();
            // dd($similarUsers);

            $recommendations = null;

            // get the recommendations


            // if($similarUsers->isNotEmpty()){
            //     // Pluck user IDs and matched show IDs in one step
            //     $similarInterestUser = $similarUsers->pluck('user_id')->toArray();
            //     $same_show_ids = $similarUsers->pluck('matched_show_ids')->flatten()->unique()->toArray();

            //     // Get lists of removed shows and watchlist shows
            //     $removed_shows = RemovedShow::where('user_id', $id)->pluck('show_id')->toArray();
            //     $watchList = Show::where(['user_id'=> $id , 'type' => 'watchlist'])->pluck('show_id')->toArray();

            //     // Merge all show IDs to exclude
            //     $excluded_show_ids = array_merge($same_show_ids, $removed_shows, $watchList);

            //     // Fetch recommendations efficiently
            //     $recommendations = DB::table('shows')
            //         ->select(
            //             'show_id',
            //             DB::raw('MAX(user_id) as user_id'), 
            //             DB::raw('MAX(name) as name'),
            //             DB::raw('MAX(image) as image'),
            //             DB::raw('MAX(genres) as genres'),
            //             DB::raw('MAX(type) as type')
            //         )
            //         ->whereIn('user_id', $similarInterestUser)
            //         ->where('type', 'shows-love')
            //         ->whereNotIn('show_id', $excluded_show_ids)  // Exclude matched, removed, and watchlist shows in one go
            //         ->groupBy('show_id')
            //         ->get();
            // }


            if ($similarUsers->isNotEmpty()) {

                // $similarInterestUser = $similarUsers->pluck('user_id')->toArray();
                // $same_show_ids = $similarUsers->pluck('matched_show_ids')->toArray();

                // $matched_show_ids = [];
                // foreach ($same_show_ids as $same_show_id) {
                //     $matched_show_ids = array_merge($matched_show_ids, explode(',', $same_show_id));
                // }





                // $removed_shows = RemovedShow::where('user_id', $id)->pluck('show_id')->toArray();
                // $watchList = Show::where(['user_id'=> $id , 'type' => 'watchlist'])->pluck('show_id')->toArray();

                // $recommendations = DB::table('shows')
                //     ->select(
                //         'show_id',
                //         DB::raw('MAX(user_id) as user_id'), 
                //         DB::raw('MAX(name) as name'),
                //         DB::raw('MAX(image) as image'),
                //         DB::raw('MAX(genres) as genres'),
                //         DB::raw('MAX(type) as type')
                //     )
                // ->whereIn('user_id', $similarInterestUser)
                // ->whereNotIn('show_id', $matched_show_ids)
                // ->whereNotIn('show_id', $removed_shows)
                // ->whereNotIn('show_id', $watchList)
                // ->where('type', 'shows-love')
                // ->groupBy('show_id')
                // ->get();
            }
            // dd($recommendations);

            $ai_recs = AiShows::where('user_id', auth()->id())->orderByDesc('created_at')->get();
            // $ai_recs = null;
            // dd($ai_recs);
            // dd($recommendations);
            // if($id == 67){
            //     $recommendations = null;
            // }
            // dd($ai_recs);
            $callaiFunction = User::where('id', auth()->id())->first();
            $callaiFunction = $callaiFunction->remember_token;

            $isRestaurantsAdded = false;
            $restaurants = false;
            $ifRestaurants =  Restaurant::where('user_id', auth()->id())->exists();
            if ($ifRestaurants) {
                $isRestaurantsAdded = true;
            }
            if ($request->isMethod('post')) {
                $restaurants = true;
            }
            $restaurantsName = RestaurantsAi::where('user_id', auth()->id())->orderByDesc('id')->get();

            return view('home', compact('recommendations', 'ai_recs', 'callaiFunction', 'isRestaurantsAdded', 'restaurants', 'restaurantsName'));
        } else {
            return redirect()->route('index');
        }
    }
    public function friends()
    {

        $friends = null;
        $userId = Auth::user()->id ?? null;
        $latestShow = null;
        $pendingRequest = null;
        $showsCountByUser = null;
        $hasNotificationBadge = null;
        $restaurantNotification = null;
        if ($userId) {
            $userfriends = Friend::where('user_id', $userId)->pluck('friend_id');
            if ($userfriends->isNotEmpty()) {
                // Get all relevant users (friends)
                $friends = User::whereIn('id', $userfriends)->orderByDesc('id')->get();
                $hasNotificationBadge = Friend::where('friend_id', auth()->id())->pluck('friends_notification_dot', 'user_id')->toArray();
                $restaurantNotification = Friend::where('friend_id', auth()->id())->pluck('restaurant_notification_dot', 'user_id')->toArray();
            }
            $youHaveRestaurant = Restaurant::where('user_id', $userId)->exists();
            $firendsHaveRestaurant = Restaurant::whereIn('user_id', $userfriends)->exists();
            $restaurant_Added_Friends = null;
            if ($firendsHaveRestaurant) {
                $firendWithRestaurant = Restaurant::whereIn('user_id', $userfriends)->pluck('user_id');
                $restaurant_Added_Friends = User::select('name')->whereIn('id', $firendWithRestaurant)->orderByDesc('id')->get();
            }
        }

        $pendingRequest = FriendInvite::where('user_id', $userId)->orderByDesc('id')->get();
        // dd($hasNotificationBadge);
        return view('friends', compact('restaurant_Added_Friends', 'friends', 'pendingRequest', 'showsCountByUser', 'latestShow', 'restaurantNotification', 'hasNotificationBadge', 'youHaveRestaurant', 'firendsHaveRestaurant'));
    }

    public function markHeaderAsSeen()
    {

        Friend::where('friend_id', auth()->id())->update(['header_notification_dot' => 1]);

        return response()->json(['status' => 'updated']);
    }
    public function friendShowsMarkAsSeen(Request $request)
    {

        $friend_id = $request->friend_id;
        if ($request->type === 'shows') {
            Friend::where('friend_id', auth()->id())->where('user_id', $friend_id)->update(['friends_notification_dot' => 1]);
        }
        if ($request->type === 'restaurant') {
            Friend::where('friend_id', auth()->id())->where('user_id', $friend_id)->update(['restaurant_notification_dot' => 1]);
        }

        return response()->json(['status' => 'updated']);
    }


    public function watchList()
    {
        $id = Auth::user()->id;
        $watchlist = Watchlist::where(['user_id' => $id])->where('type', 'watchlist')->orderByDesc('id')->get();

        $isRestaurantsAdded = false;
        $restaurantslist = Watchlist::where(['user_id' => $id])->where('type', 'queue-restaurant')->orderByDesc('id')->get();
        $ifRestaurants =  Restaurant::where('user_id', auth()->id())->exists();
        if ($ifRestaurants) {
            $isRestaurantsAdded = true;
        }

        return view('watch-list', compact('watchlist', 'isRestaurantsAdded', 'restaurantslist'));
    }
    public function bookmarks()
    {
        $user_id = Auth::user()->id;
        $saved = SaveBookmark::where('user_id', $user_id)->where('type', 'bookmark-show')->get();
        $savedRestaurants = SaveBookmark::where('user_id', $user_id)->where('type', 'bookmark-restaurant')->get();

        $isRestaurantsAdded = false;
        $ifRestaurants =  Restaurant::where('user_id', auth()->id())->exists();
        if ($ifRestaurants) {
            $isRestaurantsAdded = true;
        }
        return view('bookmark', compact('saved', 'savedRestaurants', 'isRestaurantsAdded'));
    }

    public function showsLoved(Request $request)
    {
        // $showsList = Show::where('user_id', Auth::user()->id)->where('type','shows-love')->get();
        // $showsList = Cache::remember('shows-love-' . Auth::user()->id, now()->addMinutes(2400), function () {
        //     return Show::where('user_id', Auth::user()->id)
        //                ->where('type', 'shows-love')
        //                ->get();
        // });
        $restaurants = false;
        $showsList = Show::where('user_id', Auth::user()->id)
            ->where('type', 'shows-love')
            ->orderByDesc('id')->get();

        if ($request->isMethod('post')) {
            $restaurants = true;
        }
        $isRestaurantsAdded = false;
        $ifRestaurants =  Restaurant::where('user_id', auth()->id())->exists();
        if ($ifRestaurants) {
            $isRestaurantsAdded = true;
        }
        $restaurantsName = Restaurant::where('user_id', auth()->id())->orderByDesc('id')->get();

        return view('show-i-love', compact('showsList', 'restaurants', 'isRestaurantsAdded', 'restaurantsName'));
    }
    public function account()
    {
        $id = Auth::id();
        $user = User::select('name', 'email')->where('id', $id)->first();
        return view('model.account', compact('user'));
    }
    public function friendsShowsList($id)
    {

        $shows = Show::where('user_id', $id)->where('type', 'shows-love')->get();
        $user = User::select('name', 'id')->find($id);

        return view('model.shows-friends-love', compact('shows', 'user'));
    }
    public function friendsRestaurantList($id)
    {

        $restaurants = Restaurant::where('user_id', $id)->get();
        $user = User::select('name', 'id')->find($id);

        return view('model.restaurants-friends', compact('restaurants', 'user'));
    }

    public function addFriend(Request $request)
    {
        $text = 'Invite friend and see each other’s shows';
        $shareText = 'I’m using Pinyotta to discover TV shows and I wanted to invite you to be a friend so we can share shows we love with each other!';
         if ($request->query('type') === 'restaurant') {
            $text = 'Invite friends and see each other’s restaurants';
            $shareText = 'I’m using Pinyotta to discover restaurants and I wanted to invite you to be a friend so we can share restaurants we love with each other!';
        }
        
        return view('model.add-friend', compact('text','shareText'));
    }
 
    
    public function addShow()
    {
        return view('model.add-show');
    }
    public function addRestaurantLove()
    {
        $queue = false;
        return view('model.add-restaurant-love', compact('queue'));
    }
    public function addSingleRestaurantQueue()
    {
        $queue = true;
        return view('model.add-restaurant-love', compact('queue'));
    }
    public function addShowInWatchlist(Request $request)
    {
        return view('model.add-show-watchlist');
    }
    public function saveShowsInWatchlist(Request $request)
    {

        $userId = Auth::user()->id;
        $exists = WatchList::where('user_id', $userId)->where('show_id', $request->item_id)->where('type', 'watchlist')->exists();
        if ($exists) {
            return response()->json(['error' => true, 'message' => 'Already Exist'], 409);
        } else {
            $watch_list = new WatchList;
            $watch_list->user_id = $userId;
            $watch_list->show_id = $request->item_id;
            $watch_list->name = $request->item_name;
            $watch_list->genres = $request->genres;
            $watch_list->image = $request->item_image;
            $watch_list->type = 'watchlist';
            $watch_list->save();
            return response()->json(['message' => 'Successfully Updated!']);
        }
    }
    public function saveShowsInShowsILove(Request $request)
    {

        $userId = Auth::user()->id;
        $exists = Show::where('user_id', $userId)->where('show_id', $request->item_id)->where('type', 'shows-love')->exists();
        if ($exists) {
            return response()->json(['error' => true, 'message' => 'Already Exist'], 409);
        } else {
            $watch_list = new Show;
            $watch_list->user_id = $userId;
            $watch_list->show_id = $request->item_id;
            $watch_list->name = $request->item_name;
            $watch_list->genres = $request->genres;
            $watch_list->image = $request->item_image;
            $watch_list->type = 'shows-love';
            $watch_list->save();
            if ($watch_list->save()) {
                Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
                Friend::where('user_id', auth()->id())->update(['friends_notification_dot' => 0]);
            }

            $this->increaseDecreaseShowsCount();
            $this->addShowsILoveCache();

            return response()->json(['message' => 'Successfully Updated!']);
        }
    }

    public function addShowsILoveCache()
    {
        // Invalidate the cache after adding a new show
        // Cache::forget('shows-love-' . Auth::user()->id);
        // $userId = Auth::user()->id;
        // Cache::forget("similar_users_{$userId}_matches");

        // // Optionally, cache the result again if you want it to be immediately available
        // Cache::remember('shows-love-' . Auth::user()->id, now()->addMinutes(2400), function () {
        //     return Show::where('user_id', Auth::user()->id)
        //     ->where('type', 'shows-love')
        //     ->get();
        // });
    }
    public function remveshowfromshowsilove(Request $request)
    {
        $show_id = $request->id;
        $user_id = Auth::user()->id;
        Show::where('user_id', $user_id)->where('show_id', $show_id)->where('type', 'shows-love')->delete();
        $this->increaseDecreaseShowsCount();
        $this->addShowsILoveCache();
        return response()->json(['message' => 'successfully removed']);
    }

    public function howToWork()
    {
        return view('how-it-work');
    }
    public function signIn()
    {
        session()->forget('shows_loved');
        session()->forget('restaurants_loved');

        return view('signin-page');
    }

    public function googleAuth()
    {

        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
            ]
        );

        $hasShows = Show::where('user_id', $user->id)->exists();
        $showsLoved = session('shows_loved', []);
        $firstShow = $showsLoved[0] ?? null;

        $hasRestaurants = Restaurant::where('user_id', $user->id)->exists();
        $restaurantLoved = session('restaurants_loved');


        // Case 1: No shows and restaurants in DB and session data delete user and redirect with error
        if ((!$hasShows && is_null($firstShow)) && (!$hasRestaurants && is_null($restaurantLoved))) {
            $user->delete();
            return redirect()->back()->withErrors(['error' => 'Account not found please sign up.']);
        }

        // Case 2: No shows in DB but session has data create new show records
        if (!$hasShows && !is_null($firstShow)) {
            $shows = [];
            foreach ($firstShow['item_id'] as $index => $itemId) {
                $shows[] = [
                    'user_id' => $user->id,
                    'show_id' => $itemId,
                    'name' => $firstShow['item_name'][$index] ?? '',
                    'image' => $firstShow['item_image'][$index] ?? '',
                    'genres' => $firstShow['genres'][$index] ?? '',
                    'type' => 'shows-love',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $user->shows_count = count($shows);
            $user->remember_token = 1;
            $user->save();
            // Insert all shows in one query
            Show::insert($shows);
            session()->forget('shows_loved');
        }
        //for restaurant in same case
        if (!$hasRestaurants && !is_null($restaurantLoved)) {
            $names = $restaurantLoved['name'];
            $cities = $restaurantLoved['city'];

            foreach ($names as $index => $name) {
                $city = $cities[$index] ?? null;

                $restaurant[] = [
                    'user_id' => $user->id,
                    'name'    => $name,
                    'city'    => $city,
                ];
            }

            $user->restaurants_count = count($restaurant);
            $user->remember_token = 1;
            $user->save();

            Restaurant::insert($restaurant);
            session()->forget('restaurants_loved');
            Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
            Friend::where('user_id', auth()->id())->update(['restaurant_notification_dot' => 0]);
        }
        // Case 3: shows in DB and but session has data remove the session data and redirect back with error
        if (($hasShows && !is_null($firstShow)) || ($hasRestaurants && !is_null($restaurantLoved))) {
            session()->forget('shows_loved');
            session()->forget('restaurants_loved');
            return redirect()->back()->withErrors(['error' => 'Already record found just Sign in.']);
        }

        Auth::login($user);
        $inviteList = Session::get('fid_list');

        if (!empty($inviteList)) {
            $referralUsers = FriendInvite::whereIn('random_token', $inviteList)
                ->where('user_id', '!=', $user->id)
                ->pluck('user_id')
                ->unique()
                ->values();

            if ($referralUsers->isNotEmpty()) {
                $friendPairs = [];

                foreach ($referralUsers as $refUserId) {
                    $friendPairs[] = ['user_id' => $refUserId, 'friend_id' => $user->id];
                    $friendPairs[] = ['user_id' => $user->id, 'friend_id' => $refUserId];
                }

                Friend::insert($friendPairs);
                FriendInvite::whereIn('random_token', $inviteList)->delete();
                Session::forget('fid_list');
            }
        }

        return redirect('/home');
    }


    public function saveInvitations(Request $request)
    {
        // dd($request);
        $invites = FriendInvite::updateOrCreate(
            [
                'user_id' => $request->id,
                'random_token' => $request->invite_token,
            ],
            [
                'name' => $request->name,
            ]
        );

        if ($invites) {
            return response()->json(['message' => 'Invitation saved successfully']);
        } else {
            return response()->json(['message' => 'Failed to save invitation'], 500);
        }
    }

    public function moveToWatchlist(Request $request)
    {
        $userId = Auth::id();
        $showId = $request->id;
        $exists = WatchList::where('user_id', $userId)->where('show_id', $showId)->exists();
        // dd($request);
        if ($exists) {
            return response()->json([
                'error' => true,
                'message' => 'Already Exist'
            ], 409);
        } else {
            $show = Show::where(['show_id' => $showId])->first();
            $watch_list = new WatchList;
            $watch_list->user_id = $userId;
            $watch_list->show_id = $show->show_id;
            $watch_list->name = $show->name;
            $watch_list->genres = $show->genres;
            $watch_list->image = $show->image;
            $watch_list->type = 'watchlist';
            $watch_list->save();

            return response()->json(['message' => 'The show successfully moved to your watchlist']);
        }
    }


    public function moveToShowsLove(Request $request)
    {
        // dd($request);
        $userId = Auth::id();
        $showId = $request->id;
        $alreadyLoved = Show::where([['user_id', $userId], ['show_id', $showId], ['type', 'shows-love']])->exists();

        if ($alreadyLoved) {
            return response()->json(['error' => true, 'message' => 'Already Exist'], 409);
        }
        $watchlistShow = WatchList::where([['user_id', $userId], ['show_id', $showId]])->first();


        $originalShow = WatchList::where('show_id', $showId)->first();

        if ($request->type == 'bookmark-show') {
            $originalShow = SaveBookmark::where('show_id', $showId)->where('type', 'bookmark-show')->first();
        }
        // dd($originalShow);
        $show_love = new Show;
        $show_love->user_id = $userId;
        $show_love->show_id = $originalShow->show_id;
        $show_love->name = $originalShow->name;
        $show_love->genres = $originalShow->genres;
        $show_love->image = $originalShow->image;
        $show_love->type = 'shows-love';
        $show_love->save();
        // if($show_love->save()){
        $originalShow->delete();

        Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
        Friend::where('user_id', auth()->id())->update(['friends_notification_dot' => 0]);
        // }

        $this->increaseDecreaseShowsCount();
        // }


        $this->addShowsILoveCache();
        return response()->json([
            'message' => 'The show successfully moved to your shows I love'
        ]);
    }
    public function moveToShowsLoveFromFriends(Request $request)
    {
        $userId = Auth::id();
        $showId = $request->id;

        $alreadyLoved = Show::where([['user_id', $userId], ['show_id', $showId], ['type', 'shows-love']])->exists();

        if ($alreadyLoved) {
            return response()->json(['error' => true, 'message' => 'Already Exist'], 409);
        } else {
            $originalShow = Show::where('show_id', $showId)->first();
            $show_love = new Show;
            $show_love->user_id = $userId;
            $show_love->show_id = $originalShow->show_id;
            $show_love->name = $originalShow->name;
            $show_love->genres = $originalShow->genres;
            $show_love->image = $originalShow->image;
            $show_love->type = 'shows-love';
            $show_love->save();
            if ($show_love->save()) {
                Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
                Friend::where('user_id', auth()->id())->update(['friends_notification_dot' => 0]);
            }

            $this->increaseDecreaseShowsCount();
        }

        $this->addShowsILoveCache();
        return response()->json([
            'message' => 'The show successfully moved to your shows I love'
        ]);
    }


    public function removeShow(Request $request)
    {
        // $show =  Show::select('user_id','show_id')->where('show_id' , $request->id)->first();
        if ($request->type == 'bookmark-show') {
            SaveBookmark::where('user_id', Auth::user()->id)->where('type', 'bookmark-show')->where('show_id', $request->id)->delete();
            return response()->json(['message' => 'The show successfully removed']);
        }
        $remove_show = new RemovedShow;
        $remove_show->user_id = Auth::user()->id;
        $remove_show->show_id = $request->id;
        $remove_show->save();

        if ($request->type) {
            if ($request->type == 'watchlist') {
                WatchList::where('user_id', Auth::user()->id)->where('show_id', $request->id)->delete();
            } else if ($request->type == 'shows-love') {
                Show::where('user_id', Auth::user()->id)->where('type', $request->type)->where('show_id', $request->id)->delete();

                $this->increaseDecreaseShowsCount();
                $this->addShowsILoveCache();
            }
        }

        return response()->json(['message' => 'The show successfully removed']);
    }

    public function increaseDecreaseShowsCount()
    {
        $user = Auth::user();
        $shows_count = Show::where('user_id', $user->id)->where('type', 'shows-love')->count();
        $user->shows_count = $shows_count;
        $user->save();
        return false;
    }
    public function increaseDecreaseRestaurantCount()
    {
        $user = Auth::user();
        $restaurants_count = Restaurant::where('user_id', $user->id)->count();
        $user->restaurants_count = $restaurants_count;
        $user->save();
        return false;
    }
    public function removeRequest(Request $request)
    {

        $id = $request->id;
        FriendInvite::where('id', $id)->delete();

        return response()->json(['message' => 'successfully removed']);
    }

    public function unfriend(Request  $request)
    {
        $friendId = $request->id;
        $userId = Auth::user()->id;

        Friend::where(['user_id' => $userId, 'friend_id' => $friendId])->delete();
        Friend::where(['user_id' => $friendId, 'friend_id' => $userId])->delete();
        $unfriend = true;
        return response()->json([
            'message' => 'successfully removed',
            'unfriend' => $unfriend
        ]);
    }

    public function saveUserName(Request $request)
    {
        $update = User::where('id', Auth::id())->update(['name' => $request->name]);
        if ($update) {
            return response()->json(['message' => 'successfully updated']);
        } else {
            return response()->json([
                'error' => true,
                '   ' => 'Failed to Update!'
            ], 409);
        }
    }
    public function deleteUserAccount()
    {
        $user = Auth::user();
        FriendInvite::where('user_id', $user->id)->delete();
        Friend::where('user_id', $user->id)->delete();
        Friend::where('friend_id', $user->id)->delete();
        RemovedShow::where('user_id', $user->id)->delete();
        Show::where('user_id', $user->id)->delete();
        RecommendationLog::where('user_id', $user->id)->delete();
        AiShows::where('user_id', $user->id)->delete();
        WatchList::where('user_id', $user->id)->delete();
        Restaurant::where('user_id', $user->id)->delete();
        SaveBookmark::where('user_id', $user->id)->delete();

        // dd($user->id);

        $user->delete();

        // Logout the user
        Auth::logout();

        return response()->json(['message' => 'successfully deleted']);
    }
    public function moveToWatchlistFromAiRecs(Request $request)
    {
        $show_id = $request->id;
        $user_id = auth()->id();
        $exists = WatchList::where('user_id', $user_id)->where('show_id', $show_id)->exists();

        if ($exists) {
            return response()->json([
                'error' => true,
                'message' => 'Already Exist'
            ], 409);
        } else {
            $show = AiShows::where(['show_id' => $show_id, 'user_id' => $user_id])->first();
            if ($request->type == 'bookmark-show') {
                $show = SaveBookmark::where('show_id', $show_id)->where('type', 'bookmark-show')->first();
            }
            $watch_list = new WatchList;
            $watch_list->user_id = $user_id;
            $watch_list->show_id = $show->show_id;
            $watch_list->name = $show->name;
            $watch_list->genres = $show->genres;
            $watch_list->image = $show->image;
            $watch_list->type = 'watchlist';
            $watch_list->save();
            $show->delete();
        }
        // $this->addShowsILoveCache();
        return response()->json(['message' => 'successfully updated']);
    }
    public function moveToShowsloveFromAiRecs(Request $request)
    {
        $show_id = $request->id;
        $user_id = auth()->id();
        $exists = Show::where('user_id', $user_id)->where('show_id', $show_id)->where('type', 'shows-love')->exists();

        if ($exists) {
            return response()->json([
                'error' => true,
                'message' => 'Already Exist'
            ], 409);
        } else {

            Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
            Friend::where('user_id', auth()->id())->update(['friends_notification_dot' => 0]);

            $show = AiShows::where(['show_id' => $show_id, 'user_id' => $user_id])->first();
            $watch_list = new Show;
            $watch_list->user_id = $user_id;
            $watch_list->show_id = $show->show_id;
            $watch_list->name = $show->name;
            $watch_list->genres = $show->genres;
            $watch_list->image = $show->image;
            $watch_list->type = 'shows-love';
            $watch_list->save();

            $show->delete();
            $this->increaseDecreaseShowsCount();

            // Show::where('show_id',$show_id)->where('user_id',$user_id)->where('type','ai-recs')->update(['type'=> 'shows-love']);

        }

        $this->addShowsILoveCache();
        return response()->json(['message' => 'successfully updated']);
    }

    public function updateremebertoken()
    {
        User::where('id', auth()->id())->update(['remember_token' => 0]);
        return response()->json(['message' => 'successfully updated']);
    }

    public function removeFromAiRecs(Request $request)
    {
        $show_id = $request->id;
        $user_id = auth()->id();
        AiShows::where('user_id', $user_id)->where('show_id', $show_id)->delete();

        return response()->json(['message' => 'successfully removed']);
    }

    public function about()
    {
        return view('About');
    }
      public function terms()
    {
        return view('terms');
    }
      public function privacy()
    {
        return view('privacy');
    }

    public function moveToBookMarksFromRecommendation(Request $request)
    {

        return $this->moveToBookMarks(case: 'tvshow', case_id: $request->id, from: 'ai_recs');
    }
    public function moveToBookMarksFromFriends(Request $request)
    {

        return $this->moveToBookMarks(case: 'tvshow', case_id: $request->id, from: 'friends');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    function createRestaurants($rest, $city)
    {

        foreach ($rest as $index => $restaurant) {
            Restaurant::create([
                'user_id' => auth()->id(),
                'name' => $restaurant,
                'city' => $city[$index] ?? null,
            ]);
        }
        $this->increaseDecreaseRestaurantCount();
        Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
        Friend::where('user_id', auth()->id())->update(['restaurant_notification_dot' => 0]);
        return response()->json(['message' => 'successfully updated']);
    }
    public function globalFunctionForSaveInDb(Request $request)
    {
        if ($request->category === 'restaurant') {
            if ($request->action === 'create') {
                return $this->createRestaurants(rest: $request->restaurants, city: $request->city);
            } elseif ($request->action === 'waitThencreate') {

                session([
                    'restaurants_loved' => [
                        'name' => $request->restaurants,
                        'city' => $request->city,
                    ]
                ]);

                return response()->json(['message' => 'Session data stored successfully']);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }




    public function addRestaurantToQueue(Request $request)
    {
        if ($request->type) {
            // remove the data 
            if (in_array($request->type, ['ai_rest_remove'])) {
                RestaurantsAi::where('id', $request->id)->where('user_id', auth()->id())->delete();
                return response()->json(['message' => 'Successfully Removed']);
            }
            if ($request->type == 'restaurant-remove-from-bookmark') {
                SaveBookmark::where('id', $request->id)->where('user_id', auth()->id())->where('type', 'bookmark-restaurant')->delete();
                return response()->json(['message' => 'Successfully Removed']);
            }
            if ($request->type == 'restaurant-remove-from-queue') {
                WatchList::where('id', $request->id)->where('user_id', auth()->id())->where('type', 'queue-restaurant')->delete();
                return response()->json(['message' => 'Successfully Removed']);
            }
            if ($request->type == 'restaurant-remove-from-love') {
                Restaurant::where('id', $request->id)->where('user_id', auth()->id())->delete();
                $this->increaseDecreaseRestaurantCount();
                return response()->json(['message' => 'Successfully Removed']);
            }

            // find data
            $restaurant = Restaurant::find($request->id);
            if (in_array($request->type, ['signle-restaurant-add', 'signle-restaurant-add-queue'])) {
                $restaurant = $request;
            }
            if (in_array($request->type, ['ai_rest_moveToQueue', 'ai_rest_saveToBookmark', 'ai_rest_moveToRestaurantILove', 'ai_rest_remove'])) {
                $restaurant = RestaurantsAi::find($request->id);
            }
            if(in_array($request->type ,['restaurant-move-to-love-from-queue'])){
                 $restaurant = WatchList::find($request->id);
            }
            if(in_array($request->type ,['restaurant-move-to-queue', 'restaurant-move-to-love'])){
                 $restaurant = SaveBookmark::find($request->id);
            }

            if (!$restaurant) {
                return response()->json([
                    'error' => true,
                    'message' => 'Restaurant not found',
                ], 404);
            }
            $isExists = false;
            $type = null;
            // add or move data
            if (in_array($request->type, ['restaurant-add-to-bookmark', 'ai_rest_saveToBookmark'])) {
                $isExists = SaveBookmark::where('name', $restaurant->name)->where('city', $restaurant->city)->where('user_id', auth()->id())->exists();
                $table = new SaveBookmark;
                $type = 'bookmark-restaurant';
                $table->show_id = null;
            }

            if (in_array($request->type, ['restaurant-add-to-queue', 'restaurant-move-to-queue', 'ai_rest_moveToQueue','signle-restaurant-add-queue'])) {
                $isExists = WatchList::where('name', $restaurant->name)->where('city', $restaurant->city)->where('user_id', auth()->id())->exists();
                $table = new WatchList;
                $type = 'queue-restaurant';
                $table->show_id = null;
            }
          

            if (in_array($request->type, ['restaurant-add-to-love', 'restaurant-move-to-love', 'restaurant-move-to-love-from-queue', 'signle-restaurant-add', 'ai_rest_moveToRestaurantILove',])) {
                $isExists = Restaurant::where('name', $restaurant->name)->where('city', $restaurant->city)->where('user_id', auth()->id())->exists();
                $table = new Restaurant;
                $type = null;
                
            }


            if ($isExists) {
                return response()->json([
                    'error' => true,
                    'message' => 'Already Exists',
                ], 409);
            }

            $table->user_id = auth()->id();
            $table->name = $restaurant->name;
            $table->city = $restaurant->city;
            $table->type = $type;
            $table->save();
            // reset the notification dot
            if (in_array($request->type, ['restaurant-add-to-love', 'restaurant-move-to-love', 'restaurant-move-to-love-from-queue', 'signle-restaurant-add', 'ai_rest_moveToRestaurantILove'])) {
                $this->increaseDecreaseRestaurantCount();
                Friend::where('user_id', auth()->id())->update(['header_notification_dot' => 0]);
                Friend::where('user_id', auth()->id())->update(['restaurant_notification_dot' => 0]);
            }

            if (in_array($request->type, ['restaurant-move-to-queue', 'restaurant-move-to-love'])) {
                SaveBookmark::where('id', $restaurant->id)->where('user_id', auth()->id())->where('type', 'bookmark-restaurant')->delete();
            }
            if (in_array($request->type, ['restaurant-move-to-love-from-queue',])) {
                WatchList::where('id', $restaurant->id)->where('user_id', auth()->id())->where('type', 'queue-restaurant')->delete();
            }
            if (in_array($request->type, ['ai_rest_moveToQueue', 'ai_rest_moveToRestaurantILove', 'ai_rest_remove', 'ai_rest_saveToBookmark'])) {
                RestaurantsAi::where('id', $restaurant->id)->where('user_id', auth()->id())->delete();
            }

            return response()->json(['message' => 'Successfully Updated']);
        }
        return response()->json(['error' => true, 'message' => 'Invalid type'], 400);
    }
}
