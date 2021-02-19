<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

class AccessToken extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      'access_token',
    ];
  }

  public function getAccessTokenValue()
  {
    return $this->getValue('access_token');
  }
}
