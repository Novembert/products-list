<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Tag;

class TagRepository
{
    public function getTagByNameAndColor(string $name, string $color): ?Tag
    {
        return Tag::query()->where('name', $name)->where('color', $color)->first();
    }

    public function saveTag(Tag $tag): void
    {
        $tag->save();
    }

    public function deleteTag(Tag $tag): void
    {
        $tag->delete();
    }
}
