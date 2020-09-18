<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
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
        $product = Product::orderBy('created_at', 'desc');
        
        if($search != "")
        {
            
            $product = $product->where('name', 'LIKE', "%{$search}%")->paginate($perPage);
        }
        else
        {
            
            $product = $product->paginate($perPage);
        }
   
    
        $products = ProductResource::collection($product);

   
        return response()->json(['status' => 'success', 
                             'data' => $products,
                             'pagination' => [
                                 'total' => $products->total(),
                                 'per_page' => $products->perPage(),
                                 'current_page' => $products->currentPage(),
                                 'last_page' => $products->lastPage(),
                                 'from' => $products->firstItem(),
                                 'to' => $products->lastItem()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        
        return response()->json(['status' => 'success', 'data' => new ProductResource($product)], 200);
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
    public function destroy($id)
    {
        //
    }
}
