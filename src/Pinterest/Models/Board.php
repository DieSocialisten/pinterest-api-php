<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

use DirkGroenen\Pinterest\Pinterest;

class Board extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'id',
      'name',
      'url',
      'image_cover_url',
    ];
  }

  public function getFullUrl(): string
  {
    return Pinterest::BASE_URL . ltrim($this->modelData['url'], '/');
  }

  public function getId()
  {
    return $this->getValue('id');
  }

  public function getName()
  {
    return $this->getValue('name');
  }

  public function getImageCoverUrl()
  {
    return $this->getValue('image_cover_url');
  }
}
