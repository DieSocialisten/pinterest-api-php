<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Exceptions;

use Throwable;

class PinterestException extends \Exception
{
  private string $endpoint;
  private array $payload;

  public function __construct($message = "", $code = 0, Throwable $previous = null, string $endpoint = '', array $payload = [])
  {
    parent::__construct($message, $code, $previous);

    $this->endpoint = $endpoint;
    $this->payload = $payload;
  }

  /**
   * @return string
   */
  public function getEndpoint(): string
  {
    return $this->endpoint;
  }

  /**
   * @return array
   */
  public function getPayload(): array
  {
    return $this->payload;
  }
}
