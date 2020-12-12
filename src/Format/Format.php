<?php

declare(strict_types=1);

namespace App\Format;

abstract class Format
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    abstract public function toStr(): string;

    public function __toString()
    {
        return $this->toStr();
    }
}
