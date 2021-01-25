<?php

declare(strict_types=1);

namespace DirkGroenen\Pinterest\Loggers;

interface ErrorLoggerInterface
{
  public function log(array $data): void;
}
