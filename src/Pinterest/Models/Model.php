<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

use DirkGroenen\Pinterest\Transport\Response;

abstract class Model
{
  abstract protected function getAttributesToFill(): array;

  protected array $modelData = [];

  /**
   * @param array|Response $pinterestData
   */
  public function __construct($pinterestData)
  {
    // Fill the model
    if (is_array($pinterestData)) {
      $this->fill($pinterestData);

    } elseif ($pinterestData instanceof Response) {
      $this->fill($pinterestData->data);
    }
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
