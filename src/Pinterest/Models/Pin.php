<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

class Pin extends Model
{
  public static function isDataValid(array $data): bool
  {
    if (array_key_exists('type', $data)) {
      // sometimes we're getting this alongside with items without "type" (which are actually pins):
      if ($data['type'] == 'story') {
        return false;
      }
    }

    return true;
  }

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

  public function getId()
  {
    return $this->getValue('id');
  }

  /**
   * Url to something outside of Pinterest
   *
   * @return mixed|null
   */
  public function getLink()
  {
    return $this->getValue('link');
  }

  public function getDescription()
  {
    return $this->getValue('description');
  }

  public function getCreatedAt()
  {
    return $this->getValue('created_at');
  }

  public function getImageUrl()
  {
    return $this->getValue('image_large_url');
  }

  /**
   * Url to pin
   *
   * @return mixed|null
   */
  public function getShareableUrl()
  {
    return $this->getValue('shareable_url');
  }
}
