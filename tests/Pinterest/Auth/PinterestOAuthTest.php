<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Tests\Auth;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\PinterestMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class PinterestOAuthTest extends TestCase
{
  /**
   * @test
   *
   * @throws ReflectionException
   */
  public function shouldHaveRandomStateRightAfterCreation()
  {
    $pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);

    $state = $pinterest->getAuthComponent()->getState();

    $this->assertNotEmpty($state);
  }

  /**
   * @test
   *
   * @throws ReflectionException
   */
  public function shouldKeepStateOnceReceiveIt()
  {
    $pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);

    $state = '123';
    $pinterest->getAuthComponent()->setState($state);

    $this->assertEquals($pinterest->getAuthComponent()->getState(), $state);
  }

  /**
   * @test
   * @responsefile oauth-access_token
   *
   * @throws ReflectionException|PinterestDataException|PinterestRequestException
   */
  public function shouldGetAccessTokenAfterExchange()
  {
    $pinterest = PinterestMockFactory::parseAnnotationsAndCreatePinterestMock($this);

    $accessToken = $pinterest->getAuthComponent()->exchangeCodeForAccessToken('my-code', 'https://example.com');

    self::assertEquals('access token 234', $accessToken->getAccessTokenValue());
  }

  /**
   * @test
   * @responsefile oauth-access_token
   *
   * @throws PinterestRequestException|ReflectionException|PinterestDataException
   */
  public function shouldUseRequestLoggerWhenExchangesCodeForToken()
  {
    $loggerMock = $this->createMock(RequestLoggerInterface::class);

    $loggerMock
      ->expects($this->once())
      ->method('logRequest')
      ->with(
        'https://api.pinterest.com/v3/oauth/access_token/',
        [
          'headers'         => ['Authorization' => 'Bearer my-access-token'],
          'connect_timeout' => 20,
          'timeout'         => 90,
          'verify'          => false,
          'http_errors'     => true,
          'form_params'     => [
            'grant_type'    => 'authorization_code',
            'client_id'     => '0',
            'client_secret' => '0',
            'code'          => 'my-code',
            'redirect_uri'  => 'https://example.com',
          ],
        ],
        'my-access-token'
      );

    $pinterest = PinterestMockFactory::createLoggerAwarePinterestMock($this, $loggerMock);

    $pinterest->getAuthComponent()->setAccessTokenValue('my-access-token');
    $pinterest->getAuthComponent()->exchangeCodeForAccessToken('my-code', 'https://example.com');
  }

  /**
   * @test
   */
  public function shouldBuildLoginUrl()
  {
    $expectedUrl = 'https://www.pinterest.com/oauth/?response_type=code&redirect_uri=https%3A%2F%2Fdev-app.daniele.eu.ngrok.io%2Fpinterest_auth%2Fcallback_access_token&client_id=123&scope=read_users&state=ae361bd';

    $pinterest = new Pinterest('123', '456');
    $pinterest->getAuthComponent()->setState('ae361bd');

    $actualUrl = $pinterest->getAuthComponent()->getLoginUrl(
      'https://dev-app.daniele.eu.ngrok.io/pinterest_auth/callback_access_token',
      ['read_users']
    );

    self::assertEquals($expectedUrl, $actualUrl);
  }
}
