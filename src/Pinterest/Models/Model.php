<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

use DirkGroenen\Pinterest\Transport\Response;

abstract class Model
{
  abstract protected function getAttributesToFill(): array;

  protected array $modelData = [];

  protected static function isDataValid(array $data): bool
  {
    return true;
  }

  /**
   * @param array|Response $pinterestData
   *
   * @return false|mixed|Model
   */
  public static function create($pinterestData)
  {
    if (is_array($pinterestData)) {
      $data = $pinterestData;
    } elseif ($pinterestData instanceof Response) {
      $data = $pinterestData->data;
    } else {
      return false;
    }

    if (!static::isDataValid($data)) {
      return false;
    }

    return new static($data);
  }

  private function __construct(array $data)
  {
    $this->fill($data);
  }

  private function fill(array $data)
  {
    foreach ($this->getAttributesToFill() as $attribute) {
      $this->modelData[$attribute] = array_key_exists($attribute, $data)
        ? $data[$attribute]
        : null;
    }
  }

  protected function getValue(string $key, $defaultValue = null)
  {
    return $this->modelData[$key] ?? $defaultValue;
  }
}
