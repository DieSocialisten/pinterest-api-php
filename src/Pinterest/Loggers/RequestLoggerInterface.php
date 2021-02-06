<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

interface RequestLoggerInterface
{
  public function logRequest(string $endpoint, array $payload): void;
}
