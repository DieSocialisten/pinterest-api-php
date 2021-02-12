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
   * @return User
   *
   * @throws PinterestDataException
   * @throws PinterestRequestException
   */
  public function me(): User
  {
    $endpoint = RequestMaker::buildFullUrlToEndpoint("users/me/");

    $httpResponse = $this->requestMaker->get($endpoint);

    return new User(ResponseFactory::createFromJson($httpResponse));
  }
}
