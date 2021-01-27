<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Tests\Endpoints;

use DirkGroenen\Pinterest\Models\User;
use DirkGroenen\Pinterest\Tests\PinterestAuthAwareTestCase;

class UsersTest extends PinterestAuthAwareTestCase
{
  public function testMe()
  {
    $response = $this->pinterest->users->me();

    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals("503066358284560467", $response->id);
  }
}
