<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/////admin stuff
Route::get('getAllUsers', 'UserController@getAllUsers');

/////////////////////user registration and login
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');});

//not used anymore (mobile part is considering it)
Route::get('auth/{provider}', 'socialAuthController@redirect');
Route::get('auth/{provider}/callback', 'socialAuthController@Callback');

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

//////////////////////category CRUD routes

Route::post('/category/create', 'CategoryController@create');
Route::post('/category/delete', 'CategoryController@delete');
Route::put('/category/update', 'CategoryController@update');
Route::get('/category/index', 'CategoryController@index');
Route::get('/category/show', 'CategoryController@show');
Route::get('/category/findPlansByCategorieName', 'CategoryController@findPlansByCategorieName');
Route::get('/category/getPlansByCategoryName', 'CategoryController@getPlansByCategoryName');


/////////////////////plan CRUD routes and other functionalities (like and favorite)


Route::post('/plan/create', 'PlanController@create');
Route::post('/plan/delete', 'PlanController@delete');
Route::put('/plan/update', 'PlanController@update');
Route::get('/plan/index', 'PlanController@index');
Route::get('/plan/show', 'PlanController@show');
Route::put('/plan/approve', 'PlanController@approve');
Route::get('/plan/showAllApprovedPlans', 'PlanController@showAllApprovedPlans');
Route::post('/plan/ratePlan', 'PlanController@ratePlan');
Route::put('/plan/changeRating', 'PlanController@changeRating');
Route::get('/plan/getFavoritePlans', 'PlanController@getFavoritePlans');
Route::get('/plan/getRecommandedPlans', 'PlanController@getRecommandedPlans');
Route::put('/plan/like', 'PlanController@like');
Route::put('/plan/unlike', 'PlanController@unlike');
Route::get('/plan/like/{id}', ['as' => 'plan.like', 'uses' => 'LikeController@likePlan']);
Route::post('/plan/addToFavorites', 'PlanController@addToFavorites');
Route::post('/plan/removeFromFavorites', 'PlanController@removeFromFavorites');
Route::get('/plan/favoriteCount', 'PlanController@favoriteCount');
Route::get('/plan/whoFavoritePlan', 'PlanController@whoFavoritePlan');
Route::get('/plan/isFavorited', 'PlanController@isFavorited');
Route::get('/plan/getPlansByCategory', 'PlanController@getPlansByCategory');
Route::get('/plan/getPlansByRegion', 'PlanController@getPlansByRegion');

////liking stuff
Route::post('/plan/likePlan', 'PlanController@like');
Route::post('/plan/dislikePlan', 'PlanController@dislikePlan');
Route::get('/plan/likedBy', 'PlanController@likedBy');
Route::get('/plan/unlikedBy', 'PlanController@unlikedBy');
Route::get('/plan/likesNumber', 'PlanController@likesNumber');
Route::get('/plan/dislikesNumber', 'PlanController@dislikesNumber');
Route::get('/plan/getAllPlansLikedByUser', 'PlanController@getAllPlansLikedByUser');
Route::get('/plan/mostLikedPlans', 'PlanController@mostLikedPlans');


///////////////////comment CRUD routes

Route::post('/comment/create', 'CommentController@create');
Route::get('/comment/index', 'CommentController@index');
Route::get('/comment/show', 'CommentController@show');
Route::post('/comment/update', 'CommentController@update');
Route::post('/comment/destroy', 'CommentController@destroy');

////////favorite plans


Route::get('/favourite/index', 'UserController@getFavoritePlans');

//////////profile edit
Route::post('/profile/create', 'ProfileController@create');

//////// image crud
Route::get('/image/show', 'ImageController@show');
Route::post('/image/create', 'ImageController@create');
//////profile
Route::post('/profile/create', 'ProfileController@create');


Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});
