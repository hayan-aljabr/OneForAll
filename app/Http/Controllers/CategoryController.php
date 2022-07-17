<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'unique:categories|required|string|max:60',


    ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

     $category =   Category::create([
            'name' => request('name'),
            'thecategory_id' => request('thecategory_id'),

        ]);
      //  $category = new Category();
        //$category->name = $request->input('name');
      //  $category->parent_id = $request->input('parent_id');
       if( $category->save()){
        return redirect()->route('categories.index')->with(['success'=> 'Category added successfuly']);
       } ;
        return redirect()->back()->with(['fail'=> 'Unable to add category.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->parent_id = $request->parent_id ? $request->parent_id : 0;

        if($category->save()){
            return redirect()->route('categories.index')->with(['success' => 'category updated']);
        }
        return redirect()->back()->with(['fail'=>'Unable to update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

    }
}
