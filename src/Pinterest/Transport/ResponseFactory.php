<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Transport;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use GuzzleHttp\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{
  /**
   * @param ResponseInterface $httpResponse
   * @return Response
   *
   * @throws PinterestDataException
   */
  public static function createFromJson(ResponseInterface $httpResponse): Response
  {
    $json = (string)$httpResponse->getBody();

    try {
      $responseData = \GuzzleHttp\json_decode($json, true);

    } catch (InvalidArgumentException $e) {
      throw new PinterestDataException($e->getMessage(), $e->getCode());
    }

    return new Response($responseData);
  }
}
