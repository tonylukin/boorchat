<?php

declare(strict_types=1);

namespace App\Component;

use Symfony\Component\HttpFoundation\Response;

class XmlResponse extends Response
{
    public function __construct($content = '', int $status = 200, array $headers = [])
    {
        parent::__construct($content, $status, \array_merge($headers, [
            'Content-Type' => 'text/xml',
        ]));
    }
}
