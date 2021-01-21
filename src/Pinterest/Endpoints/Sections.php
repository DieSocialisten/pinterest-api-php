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
use DirkGroenen\Pinterest\Models\Section;
use DirkGroenen\Pinterest\Models\Collection;

class Sections extends Endpoint
{
  /**
   * Create a section
   *
   * @param string $board
   * @param array $data
   * @return Section
   *
   * @throws PinterestException|CurlException
   */
  public function create(string $board, array $data): Section
  {
    $response = $this->request->put(sprintf("board/%s/sections/", $board), $data);

    return new Section($this->master, ['id' => $response->data]);
  }

  /**
   * Get sections for the given board
   *
   * @param string $board
   * @param array $data
   * @return Collection<Section>
   *
   * @throws PinterestException|CurlException
   */
  public function get(string $board, array $data = []): Collection
  {
    $response = $this->request->get(sprintf("board/%s/sections/", $board), $data);

    return new Collection(
      $this->master, array_map(
      function ($r) {
        return ['id' => $r];
      },
      $response->data
    ), "Section"
    );
  }

  /**
   * Get pins for section
   *
   * @param string $section
   * @param array $data
   * @return Collection
   *
   * @throws PinterestException|CurlException
   */
  public function pins(string $section, array $data = []): Collection
  {
    $response = $this->request->get(sprintf("board/sections/%s/pins/", $section), $data);

    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Delete a board's section
   *
   * @param string $section
   * @return bool
   *
   * @throws PinterestException|CurlException
   */
  public function delete(string $section): bool
  {
    $this->request->delete(sprintf("board/sections/%s/", $section));

    return true;
  }
}
