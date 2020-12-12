<?php

declare(strict_types=1);

namespace App\Format;

use SimpleXMLElement;

class XML extends Format
{
    public function toStr(): string
    {
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($this->data, array($xml, 'addChild'));

        return $xml->asXML();
    }
}
