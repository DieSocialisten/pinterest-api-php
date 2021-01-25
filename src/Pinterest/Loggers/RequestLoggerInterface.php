<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

interface RequestLoggerInterface
{
  public function log(string $endpoint, array $payload): void;
}
