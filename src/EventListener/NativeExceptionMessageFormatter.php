<?php
declare(strict_types=1);

namespace App\EventListener;

use Throwable;

class NativeExceptionMessageFormatter implements ExceptionMessageFormatterInterface
{
    public function getMessage(Throwable $exception): string
    {
        return $exception->getMessage();
    }
}