<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    protected $hidden = ['pivot'];


    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
