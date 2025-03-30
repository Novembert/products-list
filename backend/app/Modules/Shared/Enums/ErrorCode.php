<?php

namespace App\Modules\Shared\Enums;

enum ErrorCode: string
{
  case PRODUCT_NOT_FOUND = 'PRODUCT_NOT_FOUND';
  case UNAUTHENTICATED = 'UNAUTHENTICATED';
  case UNKNOWN_ERROR = 'UNKNOWN_ERROR';

  public function getCode(): string
  {
    return $this->value;
  }
}
