<?php

namespace App\Modules\Product\DTOs\FindOrCreateTag;

readonly class FindOrCreateTagDTO
{
    public function __construct(
        public string $name,
        public string $color,
    ) {
    }
}
