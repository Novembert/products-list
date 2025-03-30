<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\Tag;
use App\Modules\Product\DTOs\findOrCreateTag\FindOrCreateTagDTO;
use App\Modules\Product\Repositories\TagRepository;

class TagService
{
    public function __construct(protected TagRepository $tagRepository) {}

    public function findOrCreateTag(FindOrCreateTagDTO $tagDTO): Tag
    {
      $tag = $this->tagRepository->getTagByNameAndColor(
          $tagDTO->name,
          $tagDTO->color
      );

      if (!$tag) {
        $tag = new Tag();
        $tag->name = $tagDTO->name;
        $tag->color = $tagDTO->color;

        $this->tagRepository->saveTag($tag);
      }
      return $tag;
    }
}