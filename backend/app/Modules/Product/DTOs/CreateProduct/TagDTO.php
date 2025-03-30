<?php

namespace App\Modules\Product\DTOs\CreateProduct;

readonly class TagDTO
{
    public function __construct(
        public string $name,
        public string $color,
    ) {
    }
}
