<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property mixed|null id
 * @property mixed|null username
 * @property mixed|null full_name
 * @property mixed|null image_small_url
 */
class User extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'username',
      'full_name',
      'image_small_url',
    ];
  }
}
