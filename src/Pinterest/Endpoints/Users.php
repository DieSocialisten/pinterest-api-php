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

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Models\User;
use DirkGroenen\Pinterest\Transport\Response;

class Users extends Endpoint
{
  /**
   * Get the current user
   *
   * @param array $data
   * @return User
   *
   * @throws HttpClientException
   */
  public function me(array $data = []): User
  {
    $endpoint = "me/";

    $this->logViaRequestLogger($endpoint, $data);
    $responseBody = $this->request->get($endpoint, $data);

    return new User(Response::createFromJson($responseBody));
  }
}
