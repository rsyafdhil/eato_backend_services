<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'preview_image',
        'description',
        'owner_id',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
