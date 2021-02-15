<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Auth;

use DirkGroenen\Pinterest\Exceptions\PinterestDataException;
use DirkGroenen\Pinterest\Exceptions\PinterestRequestException;
use DirkGroenen\Pinterest\Models\AccessToken;
use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Transport\RequestMaker;
use DirkGroenen\Pinterest\Transport\ResponseFactory;

class PinterestOAuth
{
  private string $clientId;

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
    RequestMaker $requestMaker
  ) {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;

    $this->state = $this->generateState();

    $this->requestMaker = $requestMaker;
  }

  private function generateState(): string
  {
    return substr(md5((string)rand()), 0, 7);
  }

  /**
   * @param string $redirectUri
   * @param array $scopes
   * @param string $responseType
   * @return string
   */
  public function getLoginUrl(string $redirectUri, array $scopes, $responseType = 'code'): string
  {
    $queryParams = [
      'response_type' => $responseType,
      'redirect_uri' => $redirectUri,
      'client_id' => $this->clientId,
      'scope' => implode(',', $scopes),
      'state' => $this->state
    ];

    return sprintf('%s?%s', Pinterest::OAUTH_BASE_URL, http_build_query($queryParams));
  }

  public function getState(): ?string
  {
    return $this->state;
  }

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
   * @throws PinterestRequestException|PinterestDataException
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

    $endpoint = RequestMaker::buildFullUrlToEndpoint('oauth/access_token/');

    $httpResponse = $this->requestMaker->put($endpoint, $data);

    $accessTokenModel = AccessToken::create(ResponseFactory::createFromJson($httpResponse));

    if ($accessTokenModel === false) {
      throw new PinterestDataException("Data for access token is not valid");
    }

    return $accessTokenModel;
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
