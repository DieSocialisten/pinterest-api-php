<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property mixed|null id
 * @property mixed|null link Url to something outside of Pinterest
 * @property mixed|null description
 * @property mixed|null created_at
 * @property mixed|null image_large_url
 * @property mixed|null shareable_url Url to pin
 */
class Pin extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'link',
      'description',
      'created_at',
      'image_large_url',
      'shareable_url',
    ];
  }
}
