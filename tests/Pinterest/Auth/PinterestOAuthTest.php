<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Auth;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Tests\PinterestAuthAwareTestCase;

class PinterestOAuthTest extends PinterestAuthAwareTestCase
{
  /**
   * @test
   */
  public function shouldHaveRandomStateRightAfterCreation()
  {
    $state = $this->pinterest->getAuthComponent()->getState();

    $this->assertNotEmpty($state);
  }

  /**
   * @test
   */
  public function shouldKeepStateOnceReceiveIt()
  {
    $state = '123';
    $this->pinterest->getAuthComponent()->setState($state);

    $this->assertEquals($this->pinterest->getAuthComponent()->getState(), $state);
  }

  /**
   * @test
   * @responsefile oauth-access_token
   * @throws HttpClientException
   */
  public function shouldGetAccessTokenAfterExchange()
  {
    $accessToken = $this->pinterest->getAuthComponent()->exchangeCodeForAccessToken('my-code', 'https://example.com');

    self::assertEquals('access token 234', $accessToken->access_token);
  }
}
