<?php

declare(strict_types=1);

namespace App\Service;

use App\Format\Format;

class Serializer
{
    public Format $format;

    public function __construct(Format $format)
    {
        $this->format = $format;
    }

    public function serialize($data): string
    {
        $this->format->setData($data);

        return $this->format->toStr();
    }
}
