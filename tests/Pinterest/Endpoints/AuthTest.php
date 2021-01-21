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

use \DirkGroenen\Pinterest\Pinterest;
use \DirkGroenen\Pinterest\Tests\Utils\CurlBuilderMock;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class AuthTest extends TestCase
{
  private Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $curlBuilder = CurlBuilderMock::create($this);

    // Setup Pinterest
    $this->pinterest = new Pinterest("0", "0", $curlBuilder);
    $this->pinterest->auth->setOAuthToken("0");
  }

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
