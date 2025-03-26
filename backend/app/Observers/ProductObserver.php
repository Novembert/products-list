<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Tag;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $oldTagId = $product->getOriginal('tag_id');
        $newTagId = $product->tag_id;
        if ($oldTagId === $newTagId) {
            return;
        }

        $oldTag = Tag::find($oldTagId);
        if ($oldTag && $oldTag->products->count() === 0) {
            $oldTag->delete();
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $tag = Tag::find($product->tag_id);
        if ($tag && $tag->products->count() === 0) {
            $tag->delete();
        }
    }
}
