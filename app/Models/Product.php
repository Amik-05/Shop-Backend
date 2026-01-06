<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'stock',
        'slug',
    ];

    protected static function booted() : void
    {
        static::creating(function (Product $product)
        {
            $product->slug = Str::slug($product->name);
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
