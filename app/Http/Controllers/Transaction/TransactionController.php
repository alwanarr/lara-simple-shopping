<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $transaction = Transaction::with(['product', 'buyer'])->get();
       $transaction =  TransactionResource::collection($transaction);
       return response()->json(['status' => 'success', 'data' => $transaction], 200);
        
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with(['product', 'buyer'])->findOrFail($id);
        $transaction = new TransactionResource($transaction);
        return response()->json(['status' => 'success', 'data' => $transaction]);
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
