<?php
declare(strict_types=1);


namespace App\EventListener;

use Throwable;

interface ExceptionMessageFormatterInterface
{
    public function getMessage(Throwable $exception) :string;
}