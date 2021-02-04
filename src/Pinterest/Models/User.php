<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property mixed|null id
 * @property mixed|null username
 * @property mixed|null first_name
 * @property mixed|null last_name
 * @property mixed|null image
 */
class User extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'username',
      'first_name',
      'last_name',
      'bio',
      'created_at',
      'counts',
      'image',
      'url',
      'account_type'
    ];
  }
}
