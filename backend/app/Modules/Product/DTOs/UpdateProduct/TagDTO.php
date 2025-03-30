<?php

namespace App\Modules\Product\DTOs\UpdateProduct;

readonly class TagDTO
{
    public function __construct(
        public string $name,
        public string $color,
    ) {}
}