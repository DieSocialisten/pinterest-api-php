<?php

declare(strict_types=1);

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
