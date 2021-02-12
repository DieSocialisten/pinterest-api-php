<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property mixed|null id
 * @property mixed|null url
 * @property mixed|null note
 * @property mixed|null created_at
 */
class Pin extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'url',
      'created_at',
      'note',
    ];
  }
}
