<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

/**
 * @property string id
 */
class Board extends Model
{
  protected function getAttributesToFill(): array
  {
    return ['id', 'name', 'url', 'description', 'creator', 'created_at', 'counts', 'image'];
  }
}
