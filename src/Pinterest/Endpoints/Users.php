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

use DirkGroenen\Pinterest\Exceptions\CurlException;
use DirkGroenen\Pinterest\Exceptions\PinterestException;
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
   * @throws PinterestException|CurlException
   */
  public function me(array $data = []): User
  {
    $response = $this->request->get("me/", $data);

    return new User($this->master, $response);
  }

  /**
   * Get the provided user
   *
   * @access public
   * @param string $username
   * @param array $data
   * @return User
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function find(string $username, array $data = []): User
  {
    $response = $this->request->get(sprintf("users/%s/", $username), $data);

    return new User($this->master, $response);
  }

  /**
   * Get the authenticated user's pins
   *
   * @param array $data
   * @return Collection
   * @throws PinterestException|CurlException
   */
  public function getMePins(array $data = []): Collection
  {
    $response = $this->request->get("me/pins/", $data);

    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Search in the user's pins
   *
   * @param string $query
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function searchMePins(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $response = $this->request->get("me/search/pins/", $data);

    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Search in the user's boards
   *
   * @param string $query
   * @param array $data
   * @return Collection
   * @throws PinterestException
   * @throws CurlException
   */
  public function searchMeBoards(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $response = $this->request->get("me/search/boards/", $data);

    return new Collection($this->master, $response, "Board");
  }

  /**
   * Get the authenticated user's boards
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function getMeBoards(array $data = []): Collection
  {
    $response = $this->request->get("me/boards/", $data);

    return new Collection($this->master, $response, "Board");
  }

  /**
   * Get the authenticated user's likes
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function getMeLikes(array $data = []): Collection
  {
    $response = $this->request->get("me/likes/", $data);

    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Get the authenticated user's followers
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function getMeFollowers(array $data = []): Collection
  {
    $response = $this->request->get("me/followers/", $data);

    return new Collection($this->master, $response, "User");
  }

}
