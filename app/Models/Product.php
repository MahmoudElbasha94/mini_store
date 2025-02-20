<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'discount'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_product')->withPivot('is_default');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
