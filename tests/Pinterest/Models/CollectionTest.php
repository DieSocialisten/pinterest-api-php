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

class CollectionTest extends TestCase
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

  /**
   * @responsefile    interestsPageOne
   */
  public function testIfCollectionAllReturnsItems()
  {
    $response = $this->pinterest->following->interests();

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertTrue(is_array($response->all()));
  }

  /**
   * @responsefile    interestsPageOne
   */
  public function testIfCollectionGetReturnsCorrectAlbum()
  {
    $response = $this->pinterest->following->interests();

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Interest", $response->get(1));
    $this->assertEquals("955147773988", $response->get(1)->id);
  }

  /**
   * @responsefile    interestsPageOne
   */
  public function testIfCollectionHasNextPage()
  {
    $response = $this->pinterest->following->interests();

    $this->assertTrue($response->hasNextPage());
  }

  /**
   * @responsefile    interestsPageOne
   */
  public function testIfCollectionDecodesToJson()
  {
    $response = $this->pinterest->following->interests();

    $this->assertTrue(is_string($response->toJson()));
  }

  /**
   * @responsefile    interestsPageOne
   */
  public function testIfCollectionDecodesToArray()
  {
    $response = $this->pinterest->following->interests();

    $this->assertTrue(is_array($response->toArray()));
  }
}
