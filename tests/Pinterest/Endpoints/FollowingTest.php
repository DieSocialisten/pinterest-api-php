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

class FollowingTest extends TestCase
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

  public function testUsers()
  {
    $response = $this->pinterest->following->users();

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\User", $response->get(0));
  }

  public function testInterests()
  {
    $response = $this->pinterest->following->interests();

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Interest", $response->get(0));
  }

  public function testBoards()
  {
    $response = $this->pinterest->following->boards();

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Board", $response->get(0));
  }

  public function testFollowUser()
  {
    $response = $this->pinterest->following->followUser("181692303625162433");

    $this->assertTrue($response);
  }

  public function testUnfollowUser()
  {
    $response = $this->pinterest->following->unfollowUser("181692303625162433");

    $this->assertTrue($response);
  }

  public function testFollowBoard()
  {
    $response = $this->pinterest->following->unfollowBoard("503066289565421201");

    $this->assertTrue($response);
  }

  public function testUnfollowBoard()
  {
    $response = $this->pinterest->following->unfollowBoard("503066289565421201");

    $this->assertTrue($response);
  }

  public function testIfPaginationIsWorking()
  {
    $response = $this->pinterest->following->interests(
      array(
        "cursor" => "Pz8wMDA5NzZiMmJmYzYyYmQ5NDE0ZTFkYmMxMzIwMDc2Ynw1N2ZmMGQ4ZGRiZjMxMjQ5YjAwMmY5MGI3ZTI3NTBjNzZmNTQ4ZmFiNjNiOWYxNzE2MjdkNzgxNmMwZDNkNzFi"
      )
    );

    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Collection", $response);
    $this->assertInstanceOf("DirkGroenen\Pinterest\Models\Interest", $response->get(0));
    $this->assertEquals("932253650197", $response->get(0)->id);
  }
}
