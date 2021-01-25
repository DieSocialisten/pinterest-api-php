<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestException;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Transport\Request;

class Endpoint
{
  protected Request $request;

  protected Pinterest $master;

  public function __construct(Request $request, Pinterest $master)
  {
    $this->request = $request;
    $this->master = $master;
  }

  protected static function createPinterestException(
    \Exception $e,
    string $endpoint,
    array $payload = []
  ): PinterestException {
    return new PinterestException($e->getMessage(), $e->getCode(), $e->getPrevious(), $endpoint, $payload);
  }
}
