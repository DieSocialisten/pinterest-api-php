<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Tests;

class PinterestTest extends PinterestAuthAwareTestCase
{
  public function testGetRateLimit()
  {
    $rateLimit = $this->pinterest->getRateLimit();
    $this->assertEquals(1000, $rateLimit);
  }

  public function testGetRateLimitRemaining()
  {
    $rateLimitRemaining = $this->pinterest->getRateLimitRemaining();
    $this->assertEquals('unknown', $rateLimitRemaining);
  }
}
