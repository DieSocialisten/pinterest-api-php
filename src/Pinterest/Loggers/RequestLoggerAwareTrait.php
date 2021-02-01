<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

trait RequestLoggerAwareTrait
{
  private ?RequestLoggerInterface $requestLogger = null;

  public function setRequestLogger(?RequestLoggerInterface $requestLogger): RequestLoggerAwareTrait
  {
    $this->requestLogger = $requestLogger;

    return $this;
  }

  public function logRequest(string $endpoint, array $payload)
  {
    if (!$this->requestLogger) {
      return;
    }

    // remove sensitive data:
    unset($payload['client_id'], $payload['client_secret']);

    $this->requestLogger->log($endpoint, $payload);
  }
}
