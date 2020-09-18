<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Resources\ProductResource;

class SellerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products()
                       ->get();

        $products = ProductResource::collection($products);
                    
        return response()->json(['status' => 'success', 'data' =>   $products], 200);
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
    public function store(Request $request, User $seller)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'description'=> 'required',
            'quantity'=> 'required|integer|min:1',
            'image' => 'required|image'
        ]);

        $data = $request->all();
        if(!$validator->fails())
        {
            $data['status'] = Product::UNAVAILABLE_PRODUCT;
            $data['slug'] = \Str::slug($request->name);
            $data['image'] = '1.jpg';
            $data['seller_id'] = $seller->id;
            
            $product = Product::create($data);
            return response()->json(['status' => 'success', 'data' => $product], 200);

        }

    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $validator = \Validator::make($request->all(), [
          
            'quantity'=> 'integer|min:1',
            'status' => 'in:'.Product::AVAILABLE_PRODUCT . ', ' .Product::UNAVAILABLE_PRODUCT,
            'image' => 'image',
        ]);

        $data = $request->all();
        if(!$validator->fails())
        {
            $this->checkSeller($seller, $product);
            $product->fill(Input::only('name', 'description', 'quantity'));
            
            if($request->has('status'))
            {
                $product->status = $request->status;

                if($product->isAvailable() && $product->categories()->count() == 0)
                {
                    return response()->json(['error', 'product yang aktif harus memiliki kategori'], 499);
                }
            }

            if($product->isClean())
            {
                return response()->json(['error' => 'tidak ada data yang di ubah'], 422);
            }

            $product->save();
            return response()->json(['status' => 'success', 'data' => $product], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->delete();
        return response('Product has been deleted', 204);
    }

    protected function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id)
        {
            throw new HttpException(422, 'produk tidak dimiliki oleh penjual');
        }
    }
}
