<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_name',
        'parent_category_id',
        'slug',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'sub_category__item_id');
    }
}
