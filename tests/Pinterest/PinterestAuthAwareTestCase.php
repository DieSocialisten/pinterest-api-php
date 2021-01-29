<?php

namespace DirkGroenen\Pinterest\Tests;

use DirkGroenen\Pinterest\Pinterest;
use DirkGroenen\Pinterest\Tests\Utils\HttpClientMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

abstract class PinterestAuthAwareTestCase extends TestCase
{
  protected Pinterest $pinterest;

  /**
   * @throws ReflectionException
   */
  public function setUp(): void
  {
    $httpClient = HttpClientMockFactory::create($this);

    $this->pinterest = new Pinterest("0", "0", $httpClient);
    $this->pinterest->getAuthComponent()->setAccessTokenValue("0");
  }
}
