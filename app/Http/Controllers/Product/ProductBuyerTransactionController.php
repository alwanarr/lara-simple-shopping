<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductBuyerTransactionController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
       
        $validator = \Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if(!$validator->fails())
        {
            if($buyer->id == $product->seller_id)
            {
                return response()->json(['status' => 'Anda membeli barang anda sendiri'], 409);
            }

            if(!$buyer->isVerified())
            {
                return response()->json(['status' => ' pembeli harus terverifikasi'], 409);
            }

            if(!$product->seller->isVerified())
            {
                return response()->json(['status' => ' penjual harus terverifikasi'], 409);
            }

            if(!$product->isAvailable())
            {
                return response()->json(['status' => 'produk tidak ditemukan'], 409);
            }

            if($product->quantity < $request->quantity)
            {
                return response()->json(['status' => 'produk tidak memiliki jumlah transaksi'], 409);
            }
            

            return DB::transaction(function() use($buyer, $request, $product) {
                $product->quantity -= $request->quantity;
                $product->save();
                
                $transaction = Transaction::create([
                    'quantity' => $request->quantity,
                    'buyer_id' => $buyer->id,
                    'product_id' => $product->id,
                ]);
                return response()->json(['status' => 'success' , 'data' => $transaction], 204);
            });
        }
    }

   
}
