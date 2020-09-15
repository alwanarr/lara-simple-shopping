<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Buyer;
use App\Transaction;
class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyer = Buyer::has('transactions')->get();
        return response()->json(['data' => $buyer], 200);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Buyer $buyer)
    {
        // dd($buyer->name);
        $data = $buyer::has('transactions')->find($buyer->id);

        return response()->json(['data' => $data], 200);
    }

   
}
