<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function index(Request $request)
    {
        $image= Image::where('plan_id',$request->get('plan_id'))->toArray();
        return response()->json([
            'data' => $image]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $image = Image::create([

            'id' => $request->get('id'),
            'user_id' => $request->get('user_id'),
            'plan_id' => $request->get('plan_id'),
            'url' => $request->get('url'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $image= Image::where('plan_id',$request->get('plan_id'));
        return response()->json([
           'data' => $image->get()]);
        //return $image->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$userId)
    {
        $user= User::find($userId);
        $image= Image::find($id);

        if ($user->role_id==2 || $image->user_id==$userId)
            $image->delete();

    }
}
