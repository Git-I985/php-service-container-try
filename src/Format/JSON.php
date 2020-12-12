<?php

declare(strict_types=1);

namespace App\Format;

class JSON extends Format
{
    public function toStr(): string
    {
        return json_encode($this->data);
    }
}
