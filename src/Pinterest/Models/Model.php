<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Models;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Transport\Response;
use JsonSerializable;

abstract class Model implements JsonSerializable
{
  abstract protected function getAttributesToFill(): array;

  /**
   * The model's attributes
   *
   * @var array
   */
  protected array $attributes = [];

  /**
   * @param mixed $modelData
   */
  public function __construct($modelData = null)
  {
    // Fill the model
    if (is_array($modelData)) {
      $this->fill($modelData);

    } elseif ($modelData instanceof Response) {
      $this->fill($modelData->data);
    }
  }

  /**
   * Fill the attributes
   *
   * @param array $attributes
   */
  private function fill(array $attributes)
  {
    foreach ($attributes as $key => $value) {
      if ($this->isFillable($key)) {
        $this->attributes[$key] = $value;
      }
    }
  }

  /**
   * Check if the key is fillable
   *
   * @param string $key
   * @return bool
   */
  public function isFillable(string $key): bool
  {
    return in_array($key, $this->getAttributesToFill());
  }

  /**
   * Get the model's attribute
   *
   * @param string $key
   * @return mixed
   */
  public function __get(string $key)
  {
    return $this->attributes[$key] ?? null;
  }

  /**
   * Set the model's attribute
   *
   * @param string $key
   * @param mixed $value
   *
   * @throws PinterestDataException
   */
  public function __set(string $key, $value)
  {
    if (!$this->isFillable($key)) {
      throw new PinterestDataException(sprintf("%s is not a fillable attribute.", $key));
    }

    $this->attributes[$key] = $value;
  }

  /**
   * Check if the model's attribute is set
   *
   * @param $key
   * @return bool
   */
  public function __isset($key): bool
  {
    return array_key_exists($key, $this->attributes);
  }

  /**
   * Convert the object into something JSON serializable.
   *
   * @return array
   */
  public function jsonSerialize(): array
  {
    return $this->toArray();
  }

  /**
   * Convert the model instance to an array
   *
   * @return array
   */
  public function toArray(): array
  {
    $array = array();

    foreach ($this->getAttributesToFill() as $key) {
      $array[$key] = $this->{$key};
    }

    return $array;
  }

  /**
   * Convert the model to its string representation
   *
   * @return string
   */
  public function __toString(): string
  {
    return $this->toJson();
  }

  /**
   * Convert the model instance to JSON
   *
   * @return string
   */
  public function toJson(): string
  {
    return json_encode($this->toArray());
  }
}
