<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $fillable = [
        'name', 'category_id', 'image', 'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
