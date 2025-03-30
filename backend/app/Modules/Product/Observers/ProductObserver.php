<?php

namespace App\Modules\Product\Observers;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Tag;
use App\Modules\Product\Repositories\TagRepository;

class ProductObserver
{
    public function __construct(protected TagRepository $tagRepository)
    {
    }
    
    public function creating(Product $product): void
    {
        $maxValue = Product::max('position') ?? 0;
        $product->position = $maxValue + 1;
    }

    public function updating(Product $product): void
    {
        if (!$product->isDirty('tag_id')) {
            return;
        }

        $oldTagId = $product->getOriginal('tag_id');
        $oldTag = Tag::find($oldTagId);
        if ($oldTag && $oldTag->products->count() === 1) {
            $this->tagRepository->deleteTag($oldTag);
        }
    }

    public function deleted(Product $product): void
    {
        $tag = $product->tag_id ? Tag::find($product->tag_id) : null;
        if ($tag && $tag->products->count() === 0) {
            $this->tagRepository->deleteTag($tag);
        }
    }
}
