<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Component\XmlResponse;
use App\Helper\Array2XML;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function successResponse(array $data = [], $responseCode = Response::HTTP_OK): XmlResponse
    {
        return new XmlResponse($this->makeXmlFromArray($data), $responseCode);
    }

    protected function errorResponse(array $data = [], $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): XmlResponse
    {
        return new XmlResponse($this->makeXmlFromArray($data), $statusCode);
    }

    protected function makeXmlFromArray(array $data): ?string
    {
        Array2XML::init();
        $xml = Array2XML::createXML('response', $data);
        return $xml === null ? null : "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n{$xml->saveHTML()}";
    }
}
