<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request()->q;
        $perPage = request()->limit;
        $category = Category::orderBy('created_at', 'desc');
        
        if($search != "")
        {
            
            $category = $category->where('name', 'LIKE', "%{$search}%")->paginate($perPage);
        }
        else
        {
            
            $category = $category->paginate($perPage);
        }
   
    
        $categories = CategoryResource::collection($category);

   
        return response()->json(['status' => 'success', 
                             'data' => $categories,
                             'pagination' => [
                                 'total' => $categories->total(),
                                 'per_page' => $categories->perPage(),
                                 'current_page' => $categories->currentPage(),
                                 'last_page' => $categories->lastPage(),
                                 'from' => $categories->firstItem(),
                                 'to' => $categories->lastItem()
                             ]], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'description'=> 'required',
        ]);

        if(!$validator->fails())
        {
            $category = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => \Str::slug($request->name)
            ]);

            return response()->json(['status' => 'success', 'data' =>  $category], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, Category $category)
    {
        $category->fill($request->only('name', 'description'));

        if($category->isClean())
        {
            return response()->json(['error' => 'Anda tidak mengubah data apapun', 'code'=> 422], 422);
        }

        if($request->has('name'))
        {
            $category->slug =  \Str::slug($request->name);
        }
        $category->save();
        return response()->json(['status' => 'success', 'data' =>  $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response('deleted', 204);
    }
}
