<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Factory\JsonResponseFactory;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
class ExceptionListener
{
    private JsonResponseFactory $jsonResponseFactory;
    private ExceptionMessageFormatterInterface $exceptionMessageFormatter;

    /**
     * @param JsonResponseFactory $jsonResponseFactory
     * @param ExceptionMessageFormatterInterface $exceptionMessageFormatter
     */
    public function __construct(JsonResponseFactory $jsonResponseFactory, ExceptionMessageFormatterInterface $exceptionMessageFormatter)
    {
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->exceptionMessageFormatter = $exceptionMessageFormatter;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $event->setResponse($this->jsonResponseFactory->fail([
            $this->exceptionMessageFormatter->getMessage($exception)
        ]));
    }
}