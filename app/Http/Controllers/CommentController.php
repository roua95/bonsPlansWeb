<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Plan;
use App\User;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return Comment::where('plan_id',$request->get('plan_id'))->toArray();
        return response()->json([
            'data' => Comment::where('plan_id',$request->get('plan_id'))->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create (Request $request)
    {

        $comment = Comment::create([
            'text' => $request->get('text'),
            'user_id' => $request->get('user_id'),
            'plan_id' => $request->get('plan_id'),

        ]);


        $comment->save();

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
       // return Comment::where('id',$id);
         return response()->json([
              'data' => Comment::where('plan_id',$request->get('id'))->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,$userId)
    {
        //////only comment owner can update his comment
        $comment= Comment::find($id);

        if ($comment->user_id==$userId)
        {
            $comment= Comment::find($request->get('id'));
        $comment->text = $request->get('text');
        $comment->save();

       // return response()->json(compact('comment'));
            return response()->json([
                'data' => $comment]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$userId)

    {
        //////both admin and comment owner may delete a comment
        $user= User::find($userId);
    $comment= Comment::find($id);

            if ($user->role_id==2 || $comment->user_id==$userId)
        $comment->delete();

    }
}
