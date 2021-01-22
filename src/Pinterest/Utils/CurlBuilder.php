<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DirkGroenen\Pinterest\Utils;

use DirkGroenen\Pinterest\Exceptions\PinterestException;

class CurlBuilder
{

  /**
   * Contains the curl instance
   *
   * @var resource
   */
  private $curl;

  /**
   * Array containing headers from last performed request
   *
   * @var array
   */
  private array $headers;

  public function __construct()
  {
    $this->curl = curl_init();
  }

  /**
   * Return a new instance of the CurlBuilder
   *
   * @return CurlBuilder
   */
  public function create(): CurlBuilder
  {
    return new self();
  }

  /**
   * Execute the curl request
   *
   * @return false|string
   *
   * @throws PinterestException
   */
  public function execute()
  {
    return $this->execFollow();
  }

  /**
   * Function which acts as a replacement for curl's default
   * FOLLOW_LOCATION option, since that gives errors when
   * combining it with open basedir.
   *
   * @see http://slopjong.de/2012/03/31/curl-follow-locations-with-safe_mode-enabled-or-open_basedir-set/
   *
   * @return false|string
   * @throws PinterestException
   */
  private function execFollow()
  {
    $mr = 5;
    $body = null;

    if (ini_get("open_basedir") == "" && ini_get("safe_mode" == "Off")) {
      $this->setOptions(
        array(
          CURLOPT_FOLLOWLOCATION => $mr > 0,
          CURLOPT_MAXREDIRS => $mr
        )
      );
    } else {
      $this->setOption(CURLOPT_FOLLOWLOCATION, false);

      if ($mr > 0) {
        $original_url = $this->getInfo(CURLINFO_EFFECTIVE_URL);
        $newUrl = $original_url;

        $this->setOptions(
          array(
            CURLOPT_HEADER => true,
            CURLOPT_FORBID_REUSE => false
          )
        );

        do {
          $this->setOption(CURLOPT_URL, $newUrl);

          $response = curl_exec($this->curl);

          $header_size = $this->getInfo(CURLINFO_HEADER_SIZE);
          $header = substr($response, 0, $header_size);
          $body = substr($response, $header_size);

          if ($this->getErrorNumber()) {
            $code = 0;
          } else {
            $code = $this->getInfo(CURLINFO_HTTP_CODE);

            if ($code == 301 || $code == 302 || $code == 308) {
              preg_match('/Location:(.*?)\n/i', $header, $matches);
              $newUrl = trim(array_pop($matches));

            } elseif ($code >= 300 && $code <= 399) {
              throw new PinterestException('Error: Unhandled 3xx HTTP code: ' . $code);

            } else {
              $code = 0;
            }
          }
        } while ($code && --$mr);

        if (!$mr) {
          if ($mr === null) {
            trigger_error('Too many redirects.', E_USER_WARNING);
          }

          return false;
        }

        $this->headers = $this->parseHeaders($header);
      }
    }

    if (!$body) {
      $this->setOption(CURLOPT_HEADER, true);
      $response = $this->execute();

      $header_size = $this->getInfo(CURLINFO_HEADER_SIZE);
      $header = substr($response, 0, $header_size);
      $body = substr($response, $header_size);

      $this->headers = $this->parseHeaders($header);
    }

    return $body;
  }

  /**
   * Sets multiple options at the same time
   *
   * @param array $options
   * @return $this
   */
  public function setOptions(array $options = []): CurlBuilder
  {
    curl_setopt_array($this->curl, $options);

    return $this;
  }

  /**
   * Sets an option in the curl instance
   *
   * @param int $option
   * @param callable|mixed $value
   *
   * @return $this
   */
  public function setOption(int $option, $value): CurlBuilder
  {
    curl_setopt($this->curl, $option, $value);

    return $this;
  }

  /**
   * Get curl info key
   *
   * @param string $key
   * @return string|int
   */
  public function getInfo(string $key)
  {
    return curl_getinfo($this->curl, $key);
  }

  /**
   * Get last curl error number
   *
   * @return int
   */
  public function getErrorNumber(): int
  {
    return curl_errno($this->curl);
  }

  /**
   * Parse string headers into array
   *
   * @param string $headers
   * @return array
   */
  private function parseHeaders(string $headers): array
  {
    $result = array();

    foreach (explode("\n", $headers) as $row) {
      $header = explode(':', $row, 2);
      if (count($header) == 2) {
        $result[$header[0]] = trim($header[1]);
      } else {
        $result[] = $header[0];
      }
    }

    return $result;
  }

  /**
   * Check if the curl request ended up with errors
   *
   * @return int
   */
  public function hasErrors(): int
  {
    return curl_errno($this->curl);
  }

  /**
   * Get curl errors
   *
   * @return string
   */
  public function getErrors(): string
  {
    return curl_error($this->curl);
  }

  /**
   * Get headers
   *
   * @return array
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /**
   * Close the curl resource
   */
  public function close()
  {
    curl_close($this->curl);
  }
}
