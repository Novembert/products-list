<?php

namespace App\Modules\Product\Enums;

enum TagColorEnum: string
{
    case RED = 'red';
    case GREEN = 'green';
    case BLUE = 'blue';
    case YELLOW = 'yellow';
    case PURPLE = 'purple';
    case ORANGE = 'orange';
    case BLACK = 'black';

    public function getColor(): string
    {
        return $this->value;
    }
}
