<?php

namespace App\Http\Controllers;
use App\Favorite;
use App\Rating;
use Illuminate\Http\Response;

use App\Plan;
use App\Category;
use App\User;
use App\Like;
use App\Category_plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PlanController extends Controller
{
    static $totalRates;
    static $rateUsers = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
       // return Plan::All()->toArray();
        return response()->json([
            'data' => Plan::All()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        // return view('categories.create');

        $plan = Plan::create([

            'user_id' => $request->get('user_id'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'adresse' => $request->get('adresse'),
            'rate' => $request->get('rate'),
            'longitude' => $request->get('longitude'),
            'lattitude' => $request->get('lattitude'),
            'ApprovedBy' => $request->get('ApprovedBy'),


        ]);

/*
          $plan
        ->category()
            ->attach(category::where('category_name', 'café')->first());
*/
        //  return response()->json(compact([$category
        /* store(Request::$request);
             $category= new Category;
             $category->category_name=$request->get('category_name');
             $category->id=$request->get('id');*/

        $plan->save();

        //return response()->json(compact('plan'));
        return response()->json([
            'data' => $plan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    { $plan = Plan::where('title',$request->get('title'))->get();
        return response()->json([
            'data' => $plan
        ]);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $plan = Plan::find($request->get('id'));
        $plan->description = $request->get('description');

        $plan->title = $request->get('title');

        if ($request->get('user_id') != "") {
            $plan->user_id = $request->get('user_id');
        }
        if ($request->get('longitude') != "") {
            $plan->longitude = $request->get('longitude');
            if ($request->get('lattitude') != "") {
                $plan->lattitude = $request->get('lattitude');
            }
            if ($request->get('region') != "") {
                $plan->region = $request->get('region');
            }

            if ($request->get('adresse') != "") {
                $plan->adresse = $request->get('adresse');
            }
            if ($request->get('rate') != "") {
                $plan->rate = $request->get('rate');
            }


            $plan->save();

        }
        return response()->json(compact('plan'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Plan $plan)
    {
        $plan = Plan::find($request->get('id'));
        $plan->delete();
        //

    }

    public function approve(Request $request, Plan $plan)
    {
        $plan = Plan::find($request->get('id'));
        $user = User::find($request->get('user_id'));
        if ($user->role == "admin") {
            if ($plan->approvedBy != "") {    //how to insert admin logged in id (mobile part ??)
                $plan->approvedBy = $request->get('user_id');
                return "Plan successfully approved";
            } else return "Plan already approved";

        } else return "you should be an admin to approve a plan";
    }

    public function showAllApprovedPlans(Request $request)
    {
        $plan = DB::table(Plan::all());
        $approvedPlan = Plan::find(where($plan->approvedBy) != "");
        return response()->json([
            'data' => $approvedPlan->toArray()]);
    }

    public function showAllNotApprovedPlans(Request $request)
    {
        $plan = DB::table(Plan::all());

        $plan = Plan::find(where($plan->approvedBy == ""));
        return response()->json([
            'data' => $plan->toArray()]);

    }

    public function showAllApprovedPlansByUserId(Request $request, Plan $plan)
    {
        $plans = Plan::find(where($request->get('userId') == $plan->approvedBy));
        return response()->json([
            'data' => $plan->toArray()]);

    }


    public function getFavoritePlans(Request $request)
    {
        $ratedPlansByUser = DB::table('favorites')->get()->where('user_id', $request->get('user_id'))->pluck('favoriteable_id');

        foreach ($ratedPlansByUser as $p) {
            $data[]= Plan::find($p);
        }
        return response()->json([
            'data' => $data]);  }



    public function getRecommandedPlans(Request $request)
    {

        $mostRatedPlansByUser=Rating::where('rating','>=', $request->min)->get()->pluck('rateable_id');
        $mostRatedPlansByUser= $mostRatedPlansByUser->unique();

        foreach ($mostRatedPlansByUser->values() as $p){
            $recommanded[]= Plan::find($p);
        }

        return response()->json([
             //  'data' =>array_unique($recommanded)]);
            'data' =>$recommanded]);


    }



    public function ratePlan(Request $request)

    {

        request()->validate(['rate' => 'required']);

        $plan = Plan::find($request->id);

        $rating = new \willvincent\Rateable\Rating;

        $rating->rating = $request->rate;

        // $rating->user_id = auth()->user()->id;

        $rating->user_id = $request->get('user_id');
        $rating->rateable_id = $request->get('id');

        $requete = DB::table('ratings')->select('id')->where('user_id', $request->get('user_id'))->where('rateable_id', $request->get('id'))->first();
            $plan->ratings()->save($rating);
            //$plan->rate = ((integer)$plan->rate + $rating->rating)/((integer)self::$totalRates);
            $totalRates = DB::table('ratings')->count();
            $sum = DB::table('ratings')->sum('rating');


            // $plan->rate = ((integer)$plan->rate + $rating->rating)/((integer)$totalRates);
            $plan->rate = $sum / $totalRates;
            //return $requete;
            $plan->save();
            $rating->save();
            return ("plan rated successfuly !!");

    }

////////////////////////////if user changes his mind about a plan !!
    public function changeRating(Request $request)
    {
        request()->validate(['rate' => 'required']);

        $plan = Plan::find($request->id);

        $rating = new \willvincent\Rateable\Rating;

        $rating->rating = $request->rate;

        // $rating->user_id = auth()->user()->id;

        $rating->user_id = $request->get('user_id');
        //$requete = DB::table('ratings')->select('id')->where('user_id', $request->get('user_id'))->where('rateable_id', $request->get('id'))->first();
        $sum = DB::table('ratings')->sum('rating');
        $totalRates = DB::table('ratings')->count();
        echo "avant" . $sum;
        $sum = $sum - $plan->rate;
        echo "après" . $sum;

        $sum = $sum + $request->rate;
        echo "après update" . $sum;
        $plan->rate = $sum / $totalRates;
        $plan->save();
        // $requete = DB::table('ratings')->get()->where('user_id', $request->get('user_id'))->where('rateable_id', $request->get('id'));
        $requete = DB::table('ratings')->get()->where('id', 'id')->where('user_id', 'user_id')->first();
        echo $requete;

        if ($requete != null)
            //$requete->update("rating",$request->rate);
            $requete->rating = $request->rate;
        echo $requete;
    }

    public function addTofavorites(Request $request)
    {
        $plan = Plan::find($request->get('plan_id'));

        if(!$plan->isFavorited($request->get('user_id'))){
        $plan = Plan::find($request->get('plan_id'));
       // $plan->addFavorite($request->get('user_id'));
        Favorite::create([
            'user_id' => $request->get('user_id'),
            'favoriteable_id' => $request->get('plan_id')]);
        $code=1;}
        else {
            $plan->removeFavorite($request->get('user_id'));
            $code = 0;
        }
        return response()->json([
        'data' =>$plan , 'code' => $code]);

    }

    public function removeFromFavorites(Request $request)
    {
        $plan = Plan::find($request->get('plan_id'));

        $plan->removeFavorite($request->get('user_id'));
    }

    public function favoriteCount(Request $request)
    {
        $plan = Plan::find($request->get('plan_id'));
        return $plan->favoritesCount;
    }

    public function whoFavoritePlan(Request $request)
    {
        $plan = Plan::find($request->get('plan_id'));
        return response()->json([
            'data' =>$plan->favoritedBy()]);

    }

    public function isFavorited(Request $request)
    {
        $plan = Plan::find($request->get('plan_id'));

        return $plan->isFavorited($request->get('user_id'));
    }


    /////////liking stuff
    ///Liking methods
    public function mostLikedPlans()
    {
        $likeCount=array();
        //$plans=DB::table("plans")->where('id' ,'>' ,0)->pluck('id')->toArray();
        $plans=Plan::where('id' ,'>' ,0)->pluck('id');

        foreach ($plans as $index){
            $likeCount[]= array( $index => $this->likesNumber1($index));

            //   print_r($likeCount);
            // $array = collect($likeCount)->sortBy($this->likesNumber1($index),1)->toArray();
            // $likeCount[]=Plan::all()->sortByDesc($this->likesNumber1($index));
            $array = json_decode(json_encode($likeCount),true);
            $laravelArray = collect($array);
            asort($likeCount);
            //$laravelArray->sortByDesc($this->likesNumber1($index));
        }


        return $likeCount;

    }

    public function likesNumber1($plan_id){
        return Like::where("plan_id",$plan_id)->count();

    }

    public function whoLikedThisPost(Request $request){
        $likes= Like::where("plan_id",$request->plan_id)->get();
        $usersTab=array();

        foreach ($likes as $like){
            $usersTab[]=$like->user_id;

        }
        $likers=array();
        foreach ($usersTab as $user_id){
            $likers[]=User::find($user_id);

        }
        return $likers;
    }

    public function likesNumber(Request $request){
        $likes= Like::where("plan_id",$request->plan_id)->count();
          return response()->json([
            'data' => (String) $likes]);

    }

    public function isLikedByMe(Request $request)
    {
        $plan = Plan::find($request->plan_id)->first();
        if (Like::where("user_id",$request->user_id)->where("plan_id",$request->plan_id)->exists()){
            return 'true';
        }
        return 'false';
    }

    public function like(Request $request)
    {
        $existing_like = Like::all()->where('plan_id',$request->plan_id)->where('user_id',$request->user_id)->first();

        if (is_null($existing_like)) {
            Like::create([
                'plan_id' => $request->plan_id,
                'user_id' => $request->user_id,

            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }




    public function shareMultiple(Request $request){
      return  Share::page('http://jorenvanhocht.be', 'Share title')
            ->facebook()
            ->twitter()
            ->googlePlus()
            ->linkedin('Extra linkedin summary can be passed here')
            ->whatsapp();
    }



///parametres passés rayon+ GPS localisation : longitude et lattitude de l'utilisateur
    public function filterProximity(Request $request)
    {
        $lon = $request->longitude;
        $lat = $request->lattitude;
        $radius = $request->rayon;
        $closestPlans = Plan::select(

            DB::raw("*,
                              ( 6371 * acos( cos( radians($lat) ) *
                                cos( radians(lattitude ) )
                                * cos( radians(longitude ) - radians($lon)
                                ) + sin( radians($lat) ) *
                                sin( radians( lattitude) ) )
                              ) AS distance"))
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->pluck('id');
        //  return $closestPlans;


        $plans = array();
        $category_id=Category::where('category_name',$request->get('categorie'))->pluck('id');

        $plans=Category_plan::where('category_id',$category_id)->whereIn('plan_id',$closestPlans)->pluck('plan_id');
        //return $plans;
        $wantedPlans=array();

        foreach ($plans as $p){
            $wantedPlans[]=Plan::find($p);
        }
        return $wantedPlans;
    }
public function getPlansByCategory(Request $request)
{
    $category_id = Category::where('category_name', $request->get('categorie'))->pluck('id');
    $plans = array();
    $plans = Category_plan::where('category_id', $category_id)->pluck('plan_id');

    $allPlans=Plan::where('approvedBy','!=',0)->get();
   // $allPlans =Plan::all();
  return  $neededPlans=$allPlans->whereIn('id',$plans)->values();
}
public function getPlansByRegion(Request $request){
    $plan = Plan::where('region',$request->get('region'))->get();
    return response()->json([
        'data' => $plan
    ]);

}

    public function sendStatusPlan(Request $request){

        $plan =Plan::find($request->plan);
        if ($plan->approvedBy)
        $push = new PushNotification('apn');
        $message = [
            'aps' => [
                'alert' => [
                    ///if condition to change notif title or many methods ???!!!
                    'title' => '1 Notification test',
                    'body' => 'Ton plan a été ajouté !!'
                ],
                'sound' => 'default'
            ]
        ];
        $push->setMessage($message)
            ->setDevicesToken([
                '507e3adaf433ae3e6234f35c82f8a43a0d84218bff08f16ea7be0869f066c0312',
                'ac566b885e91ee74a8d12482ae4e1dfdda1e26881105dec262fcbe0e082a358',
                '507e3adaf433ae3e6234f35c82f8a43add84218bff08f16ea7be0869f066c0312',
            ]);
        $push = $push->send();
        $push->getFeedback();

}
}
