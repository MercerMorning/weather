<?php
declare(strict_types=1);

namespace App\EventListener;

use Throwable;

class PlugExceptionMessageFormatter implements ExceptionMessageFormatterInterface
{
    public function getMessage(Throwable $exception): string
    {
        return 'something went wrong';
    }
}