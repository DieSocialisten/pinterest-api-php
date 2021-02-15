<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

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

  public function getId()
  {
    return $this->getValue('id');
  }

  public function getUsername()
  {
    return $this->getValue('username');
  }

  public function getFullName()
  {
    return $this->getValue('full_name');
  }

  public function getImageUrl()
  {
    return $this->getValue('image_small_url');
  }
}
