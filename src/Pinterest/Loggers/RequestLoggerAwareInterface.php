<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

interface RequestLoggerAwareInterface
{
  public function setRequestLogger(?RequestLoggerInterface $requestLogger);

  public function logViaRequestLogger(string $endpoint, array $payload);
}
