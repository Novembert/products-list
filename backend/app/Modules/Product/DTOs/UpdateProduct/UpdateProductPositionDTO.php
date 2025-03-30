<?php

namespace App\Modules\Product\DTOs\UpdateProduct;

readonly class UpdateProductPositionDTO
{
    public function __construct(
      public int $oldPosition,
      public int $newPosition
    ) {
    }
}
