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

use DirkGroenen\Pinterest\Tests\PinterestAuthAwareTestCase;

class AuthTest extends PinterestAuthAwareTestCase
{
  public function testRandomStateIsSet()
  {
    $state = $this->pinterest->auth->getState();

    $this->assertNotEmpty($state);
  }

  public function testSetState()
  {
    $state = substr(md5(rand()), 0, 7);
    $this->pinterest->auth->setState($state);

    $this->assertEquals($this->pinterest->auth->getState(), $state);
  }
}
