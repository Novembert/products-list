<?php

namespace App\Modules\Product\DTOs\CreateProduct;

readonly class CreateProductDTO
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
