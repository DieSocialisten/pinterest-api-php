<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Exceptions;

class PinterestExceptionsFactory
{
  public static function createFromCurrentException(
    \Exception $e,
    string $endpoint,
    array $payload = []
  ): PinterestException {
    return new PinterestException($e->getMessage(), $e->getCode(), $e->getPrevious(), $endpoint, $payload);
  }
}
