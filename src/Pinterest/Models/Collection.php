<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Models;

use ArrayAccess;
use ArrayIterator;
use DirkGroenen\Pinterest\Exceptions\InvalidModelException;
use DirkGroenen\Pinterest\Exceptions\InvalidResponseException;
use DirkGroenen\Pinterest\Transport\Response;
use IteratorAggregate;
use JsonSerializable;

class Collection implements JsonSerializable, ArrayAccess, IteratorAggregate
{
  /**
   * Stores the pagination object
   *
   * @var array|bool
   */
  public $pagination;

  /**
   * The items in the collection
   *
   * @var array
   */
  private array $items = [];

  /**
   * The model of each collection item
   *
   * @var string
   */
  private string $model;

  /**
   * Response instance
   */
  private ?Response $response;

  /**
   * Construct
   *
   * @param array|Response $items
   * @param string $model
   *
   * @throws InvalidModelException|InvalidResponseException
   */
  public function __construct($items, string $model)
  {
    $this->model = $model;

    if (!class_exists($this->model)) {
      throw new InvalidModelException();
    }

    // Get items and response instance
    if (is_array($items)) {
      $this->response = null;
      $this->items = $items;

    } elseif ($items instanceof Response) {
      $this->response = $items;
      $this->items = $items->data;

    } else {
      throw new InvalidResponseException("$items needs to be an instance of Transport\Response or an array.");
    }

    // Transform the raw collection data to models
    $this->items = $this->buildCollectionModels($this->items);

    // Add pagination object
    if (isset($this->response->page) && !empty($this->response->page['next'])) {
      $this->pagination = $this->response->page;
    } else {
      $this->pagination = false;
    }
  }

  /**
   * Transform each raw item into a model
   *
   * @param array $items
   * @return Model[]
   */
  private function buildCollectionModels(array $items): array
  {
    return array_map(function($item) {
      return new $this->model($item);
    }, $items);
  }

  /**
   * Get all items from the collection
   *
   * @return array
   */
  public function all(): array
  {
    return $this->items;
  }

  /**
   * Check if their is a next page available
   *
   * @return bool
   */
  public function hasNextPage(): bool
  {
    return ($this->response != null && isset($this->response->page['next']));
  }

  /**
   * Return the item at the given index
   *
   * @param int $index
   * @return Model|null
   */
  public function get(int $index): ?Model
  {
    return $this->items[$index];
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
   * Convert the collection to an array
   *
   * @return array
   */
  public function toArray(): array
  {
    $items = [];

    foreach ($this->items as $item) {
      $items[] = $item->toArray();
    }

    return array(
      "data" => $items,
      "page" => $this->pagination
    );
  }

  /**
   * Convert the collection to its string representation
   *
   * @return string
   */
  public function __toString(): string
  {
    return $this->toJson();
  }

  /**
   * Convert the collection to JSON
   *
   * @return string
   */
  public function toJson(): string
  {
    return json_encode($this->toArray(), true);
  }

  /**
   * Determine if the given item exists.
   *
   * @param mixed $offset
   * @return bool
   */
  public function offsetExists($offset): bool
  {
    return isset($this->items[$offset]);
  }

  /**
   * Get the value for a given offset.
   *
   * @param mixed $offset
   * @return mixed
   */
  public function offsetGet($offset)
  {
    return $this->items[$offset];
  }

  /**
   * Set the value for a given offset.
   *
   * @param mixed $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value)
  {
    $this->items[$offset] = $value;
  }

  /**
   * Unset the value for a given offset.
   *
   * @param mixed $offset
   */
  public function offsetUnset($offset)
  {
    unset($this->items[$offset]);
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator(): ArrayIterator
  {
    return new ArrayIterator($this->items);
  }
}
