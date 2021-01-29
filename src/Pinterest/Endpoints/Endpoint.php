<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Endpoints;

use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Transport\Request;

class Endpoint
{
  protected Request $request;

  protected Pinterest $parentPinterest;

  public function __construct(Request $request, Pinterest $pinterest)
  {
    $this->request = $request;
    $this->parentPinterest = $pinterest;
  }
}
