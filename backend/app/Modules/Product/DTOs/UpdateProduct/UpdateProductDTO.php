<?php

namespace App\Modules\Product\DTOs\UpdateProduct;

readonly class UpdateProductDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public float $vatRate,
        public ?TagDTO $tag = null,
    ) {
    }
}
