<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

use DirkGroenen\Pinterest\Pinterest;

/**
 * @property string id
 * @property mixed|null name
 * @property mixed|null image_cover_url
 */
class Board extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'name',
      'url',
      'image_cover_url'
    ];
  }

  public function getFullUrl(): string
  {
    return Pinterest::BASE_URL . ltrim($this->attributes['url'], '/');
  }
}
