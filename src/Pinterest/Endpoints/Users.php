<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\User;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class Users extends Endpoint
{
  /**
   * @throws PinterestDataException
   * @throws PinterestRequestException
   *
   * @return User
   */
  public function me(): User
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint('users/me/');

    $httpResponse = $this->requestMaker->get($endpoint);

    $user = User::create(ResponseFactory::createFromJson($httpResponse));

    if ($user === false) {
      throw new PinterestDataException("Data for user 'me' is not valid");
    }

    return $user;
  }
}
