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

/**
 * @property mixed|null id
 * @property mixed|null username
 * @property mixed|null first_name
 * @property mixed|null last_name
 * @property mixed|null image
 */
class User extends Model
{
  protected function getAttributesToFill(): array
  {
    return [
      "id",
      "username",
      "first_name",
      "last_name",
      "bio",
      "created_at",
      "counts",
      "image",
      "url",
      "account_type"
    ];
  }
}
