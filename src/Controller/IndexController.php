<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Serializer;

class IndexController
{
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function index()
    {
        return $this->serializer->serialize([
            "Action" => "index",
            "Time" => time()
        ]);
    }
}
