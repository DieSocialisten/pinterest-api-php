<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;
use Generator;

class Endpoint
{
  protected RequestMaker $requestMaker;

  public function __construct(RequestMaker $requestMaker)
  {
    $this->requestMaker = $requestMaker;
  }

  /**
   * @param int    $maxNumberOfPages
   * @param string $endpoint
   * @param array  $parameters
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   *
   * @return Generator
   */
  protected function getAllPages(int $maxNumberOfPages, string $endpoint, array $parameters): Generator
  {
    $pagesFetched = 0;

    do {
      $httpResponse = $this->requestMaker->get($endpoint, $parameters);

      $response = ResponseFactory::createFromJson($httpResponse);

      $pagesFetched++;
      $hasNextPage = $response->hasBookmark();

      if ($hasNextPage) {
        $parameters['bookmark'] = $response->getBookmark();
      }

      yield $response;
    } while ($hasNextPage && $pagesFetched < $maxNumberOfPages);
  }
}
