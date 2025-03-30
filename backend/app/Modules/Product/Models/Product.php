<?php

namespace App\Modules\Product\Models;

use App\Modules\Product\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ProductObserver::class])]
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

    protected $attributes = [
        'vat_rate' => 0.00,
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
