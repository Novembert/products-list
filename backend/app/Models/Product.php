<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'tag_id',
        'name',
        'description',
        'price',
        'vat_rate',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
