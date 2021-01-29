<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

class Pin extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      "id",
      "link",
      "url",
      "creator",
      "board",
      "created_at",
      "note",
      "color",
      "counts",
      "media",
      "attribution",
      "image",
      "metadata",
      "original_link"
    ];
  }
}
