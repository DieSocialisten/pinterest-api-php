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
      throw $this->createPinterestException($e, $endpoint);
    }

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
   */
  public function find(string $username, array $data = []): User
  {
    $endpoint = sprintf("users/%s/", $username);

    try {
      $response = $this->request->get($endpoint, $data);
    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }

    return new User($this->master, $response);
  }

  /**
   * Get the authenticated user's pins
   *
   * @param array $data
   * @return Collection
   * @throws PinterestException
   */
  public function getMePins(array $data = []): Collection
  {
    $endpoint = "me/pins/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Pin::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Search in the user's pins
   *
   * @param string $query
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function searchMePins(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $endpoint = "me/search/pins/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Pin::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Search in the user's boards
   *
   * @param string $query
   * @param array $data
   * @return Collection
   * @throws PinterestException
   */
  public function searchMeBoards(string $query, array $data = []): Collection
  {
    $data["query"] = $query;
    $endpoint = "me/search/boards/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Board::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Get the authenticated user's boards
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function getMeBoards(array $data = []): Collection
  {
    $endpoint = "me/boards/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Board::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Get the authenticated user's likes
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function getMeLikes(array $data = []): Collection
  {
    $endpoint = "me/likes/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, Pin::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

  /**
   * Get the authenticated user's followers
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException
   */
  public function getMeFollowers(array $data = []): Collection
  {
    $endpoint = "me/followers/";

    try {
      $response = $this->request->get($endpoint, $data);

      return new Collection($this->master, $response, User::class);

    } catch (\Exception $e) {
      throw $this->createPinterestException($e, $endpoint);
    }
  }

}
