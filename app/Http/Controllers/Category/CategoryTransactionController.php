<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $seller = $category->products()
                  ->whereHas('transactions')
                  ->with('transactions')
                  ->get()
                  ->pluck('seller')
                  ->unique('id');
        return response()->json(['status' => 'success', 'data' =>  $seller], 200);
        
    }

    
}
