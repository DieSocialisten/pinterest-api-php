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

use DirkGroenen\Pinterest\Models\Section;
use DirkGroenen\Pinterest\Models\Collection;

class Sections extends Endpoint
{

  /**
   * Create a section
   *
   * @access public
   * @param string $board
   * @param array $data
   * @return Section
   * @throws \DirkGroenen\Pinterest\Exceptions\PinterestException
   */
  public function create(string $board, array $data)
  {
    $response = $this->request->put(sprintf("board/%s/sections/", $board), $data);
    return new Section($this->master, ['id' => $response->data]);
  }

  /**
   * Get sections for the given board
   *
   * @access public
   * @param string $board
   * @param array $data
   * @return Collection<Section>
   * @throws \DirkGroenen\Pinterest\Exceptions\PinterestException
   */
  public function get(string $board, array $data = [])
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
   * @access public
   * @param string $section
   * @param array $data
   * @return Collection<Pin>
   * @throws \DirkGroenen\Pinterest\Exceptions\PinterestException
   */
  public function pins(string $section, array $data = [])
  {
    $response = $this->request->get(sprintf("board/sections/%s/pins/", $section), $data);
    return new Collection($this->master, $response, "Pin");
  }

  /**
   * Delete a board's section
   *
   * @access public
   * @param string $section
   * @return Collection<Pin>
   * @throws \DirkGroenen\Pinterest\Exceptions\PinterestException
   */
  public function delete($section)
  {
    $this->request->delete(sprintf("board/sections/%s/", $section));
    return true;
  }
}
