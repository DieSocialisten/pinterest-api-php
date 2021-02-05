<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Auth;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Loggers\RequestLoggerAwareTrait;
use DirkGroenen\Pinterest\Loggers\RequestLoggerInterface;
use DirkGroenen\Pinterest\Models\AccessToken;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class PinterestOAuth
{
  use RequestLoggerAwareTrait;

  /**
   * Pinterest's oauth endpoint
   */
  public const AUTH_HOST = 'https://www.pinterest.com/oauth/';

  /**
   * The application ID
   */
  private string $clientId;

  /**
   * The app secret
   */
  private string $clientSecret;

  /**
   * "State" in Pinterest API considered as a CSRF protection token.
   * By default generated "randomly" in this lib.
   */
  private ?string $state;

  private RequestMaker $requestMaker;

  public function __construct(
    string $clientId,
    string $clientSecret,
    RequestMaker $requestMaker,
    ?RequestLoggerInterface $requestLogger = null
  ) {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;

    $this->state = $this->generateState();

    $this->requestMaker = $requestMaker;

    if ($requestLogger) {
      $this->setRequestLogger($requestLogger);
    }
  }

  private function generateState(): string
  {
    return substr(md5((string)rand()), 0, 7);
  }

  /**
   * Returns the login url
   *
   * @param string $redirectUri
   * @param array $scopes
   * @param string $responseType
   * @return string
   */
  public function getLoginUrl(string $redirectUri, $scopes = ['read_users'], $responseType = 'code'): string
  {
    $queryParams = [
      'response_type' => $responseType,
      'redirect_uri' => $redirectUri,
      'client_id' => $this->clientId,
      'scope' => implode(',', $scopes),
      'state' => $this->state
    ];

    return sprintf('%s?%s', self::AUTH_HOST, http_build_query($queryParams));
  }

  /**
   * Get the generated state
   *
   * @return string
   */
  public function getState(): ?string
  {
    return $this->state;
  }

  /**
   * Set a state manually
   *
   * @param string|null $state
   */
  public function setState(?string $state)
  {
    $this->state = $state;
  }

  /**
   * @see https://developers.pinterest.com/docs/redoc/pinner_app/#section/User-Authorization/Exchange-the-code-for-an-access-token
   *
   * @param string $code
   * @param string $redirectUri
   * @return AccessToken
   *
   * @throws HttpClientException
   */
  public function exchangeCodeForAccessToken(string $code, string $redirectUri): AccessToken
  {
    $data = [
      'grant_type' => 'authorization_code',
      'client_id' => $this->clientId,
      'client_secret' => $this->clientSecret,
      'code' => $code,
      'redirect_uri' => $redirectUri,
    ];

    $endpoint = 'oauth/access_token/';
    $this->logViaRequestLogger($endpoint, $data);
    $httpResponse = $this->requestMaker->put($endpoint, $data);

    return new AccessToken(ResponseFactory::createFromJson($httpResponse));
  }

  /**
   * Set the access token for further requests
   *
   * @param string $accessToken
   */
  public function setAccessTokenValue(string $accessToken)
  {
    $this->requestMaker->setAccessTokenValue($accessToken);
  }
}
