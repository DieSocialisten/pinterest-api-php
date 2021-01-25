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
use DirkGroenen\Pinterest\Exceptions\InvalidModelException;
use DirkGroenen\Pinterest\Exceptions\PinterestException;
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
   * @throws PinterestException|CurlException
   */
  public function me(array $data = []): User
  {
    $endpoint = "me/";
    $response = $this->request->get($endpoint, $data);

    return new User($this->master, $response);
  }

  /**
   * Get the provided user
   *
   * @param string $username
   * @param array $data
   * @return User
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function find(string $username, array $data = []): User
  {
    $endpoint = sprintf("users/%s/", $username);
    $response = $this->request->get($endpoint, $data);

    return new User($this->master, $response);
  }

  /**
   * Get the authenticated user's pins
   *
   * @param array $data
   * @return Collection
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function getMePins(array $data = []): Collection
  {
    $endpoint = "me/pins/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Pin::class);
  }

  /**
   * Search in the user's pins
   *
   * @param string $query
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function searchMePins(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $endpoint = "me/search/pins/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Pin::class);
  }

  /**
   * Search in the user's boards
   *
   * @param string $query
   * @param array $data
   * @return Collection
   * @throws PinterestException
   * @throws CurlException|InvalidModelException
   */
  public function searchMeBoards(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $endpoint = "me/search/boards/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Board::class);
  }

  /**
   * Get the authenticated user's boards
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function getMeBoards(array $data = []): Collection
  {
    $endpoint = "me/boards/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Board::class);
  }

  /**
   * Get the authenticated user's likes
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function getMeLikes(array $data = []): Collection
  {
    $endpoint = "me/likes/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Pin::class);
  }

  /**
   * Get the authenticated user's followers
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function getMeFollowers(array $data = []): Collection
  {
    $endpoint = "me/followers/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, User::class);
  }

}
