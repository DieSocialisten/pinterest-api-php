<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

use GuzzleHttp\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{
  /**
   * @param ResponseInterface $httpResponse
   * @return Response
   *
   * @throws InvalidArgumentException
   */
  public static function createFromJson(ResponseInterface $httpResponse): Response
  {
    $json = (string)$httpResponse->getBody();

    $responseData = \GuzzleHttp\json_decode($json, true);

    return new Response($responseData);
  }
}
