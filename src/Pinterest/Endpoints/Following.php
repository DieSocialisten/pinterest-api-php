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
use DirkGroenen\Pinterest\Models\Collection;

class Following extends Endpoint
{
  /**
   * Get the authenticated user's following users
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function users(array $data = []): Collection
  {
    $response = $this->request->get("me/following/users/", $data);

    return new Collection($this->master, $response, "User");
  }

  /**
   * Get the authenticated user's following boards
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function boards(array $data = []): Collection
  {
    $response = $this->request->get("me/following/boards/", $data);

    return new Collection($this->master, $response, "Board");
  }

  /**
   * Get the authenticated user's following interest
   *
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function interests(array $data = []): Collection
  {
    $response = $this->request->get("me/following/interests/", $data);

    return new Collection($this->master, $response, "Interest");
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
    $this->request->post(
      "me/following/users/",
      array(
        "user" => $user
      )
    );

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
    $this->request->delete(sprintf("me/following/users/%s/", $user));

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
    $this->request->post(
      "me/following/boards/",
      array(
        "board" => $board
      )
    );

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
    $this->request->delete(sprintf("me/following/boards/%s/", $boardId));

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
    $this->request->post(
      "me/following/interests/",
      array(
        "interest" => $interest
      )
    );

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
    $this->request->delete(sprintf("me/following/interests/%s/", $interestId));

    return true;
  }
}
