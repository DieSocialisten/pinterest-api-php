<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property string access_token
 */
class AccessToken extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'access_token'
    ];
  }
}
