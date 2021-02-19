<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

trait RequestLoggerAwareTrait
{
  private ?RequestLoggerInterface $requestLogger = null;

  public function setRequestLogger(?RequestLoggerInterface $requestLogger)
  {
    $this->requestLogger = $requestLogger;
  }

  public function logViaRequestLogger(string $endpoint, array $payload, ?string $accessToken)
  {
    if (!$this->requestLogger) {
      return;
    }

    $this->requestLogger->logRequest($endpoint, $payload, $accessToken);
  }
}
