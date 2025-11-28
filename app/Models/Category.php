<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'slug',
        'status'
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'parent_category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'category_item_id');
    }
}
