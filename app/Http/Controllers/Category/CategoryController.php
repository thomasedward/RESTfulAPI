<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules =[
            'name' => 'required|regex:/^[A-Za-z\s-_]+$/|string|max:255',
            'description' => 'required|string',

        ];
        $this->validate($request,$rules);



        ;


        $category = Category::create( $request->all());

        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'regex:/^[A-Za-z\s-_]+$/|string|max:255',
            'description' => 'string',
        ];
        $this->validate($request,$rules);

        if ($request->has('name')){
            $category->name = $request->name;
        }

        if ($request->has('description')){
            $category->description = $request->description;
        }

        if (!$category->isDirty())
        {
            return $this->errorResponse(
                'you need to specify a different  value to update',
                '422');

        }
        $category->save();
        return $this->showOne($category);

//        $category->fill($request->intersect(['name','description']));
//        if ($category->isClean())
//        {
//            return $this->errorResponse(
//                'you need to specify a different  value to update',
//                '422');
//        }
//        $category->save();
//        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
    }
}
