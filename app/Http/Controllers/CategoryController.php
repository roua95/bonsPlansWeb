<?php

namespace App\Http\Controllers;

use App\Category;
use App\Category_plan;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


        return response()->json([
            'data' => category::All()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request)
    {
        //
         // return view('categories.create');

         $category =Category::create([
             'id' => $request->get('id'),
             'category_name' => $request->get('category_name'),
        ]);

      //  return response()->json(compact([$category
   /* store(Request::$request);
        $category= new Category;
        $category->category_name=$request->get('category_name');
        $category->id=$request->get('id');*/

        $category->save();

        return response()->json(compact('category'));


    //    ]));




    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id' => ['required', 'int'],
            'category_name' => ['required', 'string', 'max:255'],

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
        $category= new Category;
        $category->category_name=$request->get('category_name');
        $category->id=$request->get('id');

        $category->save();
       return view('categories.store');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $category= Category::find($request->get('id'));
        return response()->json(compact('category'));


    }
    public function findPlansByCategorieName(Request $request)
    {
        $plans=array();
        $category_id=Category::where('category_name',$request->get('category_name'))->pluck('id');
        $plans[]=Category_plan::where('category_id',$category_id)->select('plan_id')->get();
        foreach ($plans as $plan){
            return Plan::find($plan);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request,Category $category)
    {
       $category= Category::find($request->get('id'));
      //  $category = Category::update($category->category_name,$request->get('category_name'));
        $category->category_name = $request->get('category_name');
        $category->save();

        return response()->json(compact('category'));
       /* $request->validate([

            'id' => 'required',

            'category_name' => 'required',

        ]);



        $category->update($request->all());*/

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function update(Request $request, $id)
    {
        //

        $category= Category::find($id);
        $category->name=$request->get('category_name');
        return response()->json(compact('category'));

    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id£$$$
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,Category $category)
    {
        $category= Category::find($request->get('id'));
        $category->delete();
        //

    }
    public function getPlansByCategoryName(Request $request){
        $plans=array();
        $category_id=Category::where('category_name',$request->get('category_name'))->pluck('id');
        $plans[]=Category_plan::where('category_id',$category_id)->select('plan_id')->get();
        foreach ($plans as $plan){
            $found[]= Plan::find($plan);
        }
        return $plans;
      /*  return response()->json([
            //  'data' =>array_unique($recommanded)]);
            'data' =>$found]);
      */
    }
}
