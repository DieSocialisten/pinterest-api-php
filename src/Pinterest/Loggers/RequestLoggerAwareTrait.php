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

  public function logViaRequestLogger(string $endpoint, array $payload = [])
  {
    if (!$this->requestLogger) {
      return;
    }

    // mask sensitive data before storing it somewhere:

    if (isset($payload['client_id'])) {
      $payload['client_id'] = '***';
    }

    if (isset($payload['client_secret'])) {
      $payload['client_secret'] = '***';
    }

    $this->requestLogger->logRequest($endpoint, $payload);
  }
}
