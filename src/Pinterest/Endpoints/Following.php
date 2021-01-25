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
use DirkGroenen\Pinterest\Models\Collection;
use DirkGroenen\Pinterest\Models\Interest;
use DirkGroenen\Pinterest\Models\User;

class Following extends Endpoint
{
  /**
   * Get the authenticated user's following users
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function users(array $data = []): Collection
  {
    $endpoint = "me/following/users/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, User::class);
  }

  /**
   * Get the authenticated user's following boards
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function boards(array $data = []): Collection
  {
    $endpoint = "me/following/boards/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Board::class);
  }

  /**
   * Get the authenticated user's following interest
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException|InvalidModelException
   */
  public function interests(array $data = []): Collection
  {
    $endpoint = "me/following/interests/";
    $response = $this->request->get($endpoint, $data);

    return new Collection($this->master, $response, Interest::class);
  }

  /**
   * Follow a user
   *
   * @param string $user
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function followUser(string $user): bool
  {
    $endpoint = "me/following/users/";
    $this->request->post($endpoint, ["user" => $user]);

    return true;
  }

  /**
   * Unfollow a user
   *
   * @param string $user
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function unfollowUser(string $user): bool
  {
    $endpoint = sprintf("me/following/users/%s/", $user);
    $this->request->delete($endpoint);

    return true;
  }

  /**
   * Follow a board
   *
   * @param string $board
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function followBoard(string $board): bool
  {
    $endpoint = "me/following/boards/";
    $this->request->post($endpoint, ["board" => $board]);

    return true;
  }

  /**
   * Unfollow a board
   *
   * @param string $boardId
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function unfollowBoard(string $boardId): bool
  {
    $endpoint = sprintf("me/following/boards/%s/", $boardId);
    $this->request->delete($endpoint);

    return true;
  }

  /**
   * Follow a board
   *
   * @param string $interest
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function followInterest(string $interest): bool
  {
    $endpoint = "me/following/interests/";
    $this->request->post($endpoint, ["interest" => $interest]);

    return true;
  }

  /**
   * Unfollow an interest
   *
   * @param string $interestId
   * @return bool
   *
   * @throws PinterestException
   * @throws CurlException
   */
  public function unfollowInterest(string $interestId): bool
  {
    $endpoint = sprintf("me/following/interests/%s/", $interestId);
    $this->request->delete($endpoint);

    return true;
  }
}
