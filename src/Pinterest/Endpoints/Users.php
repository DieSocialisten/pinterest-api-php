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
use DirkGroenen\Pinterest\Exceptions\PinterestExceptionsFactory;
use DirkGroenen\Pinterest\Models\Board;
use DirkGroenen\Pinterest\Models\Pin;
use DirkGroenen\Pinterest\Models\User;
use DirkGroenen\Pinterest\Models\Collection;

class Users extends Endpoint
{

  /**
   * Get the current user
   *
   * @param array $data
   * @return User
   *
   * @throws PinterestException
   */
  public function me(array $data = []): User
  {
    $endpoint = "me/";

    try {
      $response = $this->request->get($endpoint, $data);
    } catch (\Exception $e) {
      throw PinterestExceptionsFactory::createFromCurrentException($e, $endpoint);
    }

    return new User($this->master, $response);
  }
}
