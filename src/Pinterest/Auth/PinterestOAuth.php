<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Auth;

use DirkGroenen\Pinterest\Exceptions\HttpClientException;
use DirkGroenen\Pinterest\Models\AccessToken;
use DirkGroenen\Pinterest\Transport\Request;

class PinterestOAuth
{
  /**
   * Pinterest's oauth endpoint
   */
  public const AUTH_HOST = "https://www.pinterest.com/oauth/";

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

  /**
   * A reference to the request instance
   */
  private Request $request;

  public function __construct(string $clientId, string $clientSecret, Request $request)
  {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;

    // Generate and set the state
    $this->state = $this->generateState();

    // Set request instance
    $this->request = $request;
  }

  /**
   * @return string
   */
  private function generateState(): string
  {
    return substr(md5(rand()), 0, 7);
  }

  /**
   * Returns the login url
   *
   * @param string $redirectUri
   * @param array $scopes
   * @param string $responseType
   * @return string
   */
  public function getLoginUrl(string $redirectUri, $scopes = ["read_users"], $responseType = "code"): string
  {
    $queryParams = [
      "response_type" => $responseType,
      "redirect_uri" => $redirectUri,
      "client_id" => $this->clientId,
      "scope" => implode(",", $scopes),
      "state" => $this->state
    ];

    // Build url and return it
    return sprintf("%s?%s", self::AUTH_HOST, http_build_query($queryParams));
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
      "grant_type" => "authorization_code",
      "client_id" => $this->clientId,
      "client_secret" => $this->clientSecret,
      "code" => $code,
      "redirect_uri" => $redirectUri,
    ];

    $response = $this->request->put("oauth/access_token/", $data);

    return new AccessToken($response);
  }

  /**
   * Set the access token for further requests
   *
   * @param string $accessToken
   */
  public function setAccessTokenValue(string $accessToken)
  {
    $this->request->setAccessTokenValue($accessToken);
  }
}
