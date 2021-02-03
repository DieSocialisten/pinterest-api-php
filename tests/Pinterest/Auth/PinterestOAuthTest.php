<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Auth;

use DirkGroenen\Pinterest\Auth\PinterestOAuth;
use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use DirkGroenen\Pinterest\Transport\Request;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class PinterestOAuthTest extends TestCase
{
  /**
   * @test
   * @throws ReflectionException
   */
  public function shouldHaveRandomStateRightAfterCreation()
  {
    $pinterest = PinterestMockFactory::createDefaultPinterestMock($this);

    $state = $pinterest->getAuthComponent()->getState();

    $this->assertNotEmpty($state);
  }

  /**
   * @test
   * @throws ReflectionException
   */
  public function shouldKeepStateOnceReceiveIt()
  {
    $pinterest = PinterestMockFactory::createDefaultPinterestMock($this);

    $state = '123';
    $pinterest->getAuthComponent()->setState($state);

    $this->assertEquals($pinterest->getAuthComponent()->getState(), $state);
  }

  /**
   * @test
   * @responsefile oauth-access_token
   * @throws HttpClientException
   * @throws ReflectionException
   */
  public function shouldGetAccessTokenAfterExchange()
  {
    $pinterest = PinterestMockFactory::createDefaultPinterestMock($this);

    $accessToken = $pinterest->getAuthComponent()->exchangeCodeForAccessToken('my-code', 'https://example.com');

    self::assertEquals('access token 234', $accessToken->access_token);
  }

  /**
   * @test
   *
   * @throws HttpClientException
   */
  public function shouldUseRequestLoggerWhenExchangesCodeForToken()
  {
    $loggerMock = $this->createMock(RequestLoggerInterface::class);

    $loggerMock
      ->expects($this->once())
      ->method('log')
      ->with(
        'oauth/access_token/',
        [
          "grant_type" => "authorization_code",
          "code" => 'my-code',
          "redirect_uri" => 'https://example.com',
          "client_id" => '***',
          "client_secret" => '***'
        ]
      );

    $requestMock = $this->createMock(Request::class);
    $requestMock->method('put')->willReturn('{}');

    $pinterestOAuth = new PinterestOAuth('doesnt matter', 'doesnt matter too', $requestMock, $loggerMock);

    $pinterestOAuth->exchangeCodeForAccessToken('my-code', 'https://example.com');
  }
}
