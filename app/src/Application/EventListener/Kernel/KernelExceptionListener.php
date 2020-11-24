<?php

namespace App\Application\EventListener\Kernel;

use App\Application\Http\Exception\ValidationHttpException;
use FOS\RestBundle\FOSRestBundle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class KernelExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->attributes->get(FOSRestBundle::ZONE_ATTRIBUTE, true)) {
            return;
        }

        if ($this->isApiRequest($event->getRequest())) {
            $this->handleException($event, $event->getThrowable());
        }
    }

    protected function isApiRequest(Request $request)
    {
        return 'application/json' === $request->headers->get('Content-Type')
            || 0 === strpos($request->getPathInfo(), '/api/');
    }

    protected function handleException(ExceptionEvent $event, \Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            switch (true) {
                case $exception instanceof ValidationHttpException:
                    $message = $this->formatValidationErrors($exception->getErrors());
                    break;
                default:
                    $message = ['_system' => str_replace(' ', '_', mb_strtoupper($exception->getMessage()))];
                    break;
            }

            $event->setResponse(new JsonResponse(['errors' => $message], $exception->getStatusCode()));
        } elseif ($exception instanceof \RuntimeException) {
            $event->setResponse(new JsonResponse(
                ['errors' => ['_system' => str_replace(' ', '_', mb_strtoupper($exception->getMessage()))]],
                $exception->getCode() ?: 500,
            ));
        }
    }

    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            if ($constraint = $violation->getConstraint()) {
                try {
                    $message = $constraint->getErrorName($violation->getCode());
                } catch (\Exception $e) {
                    continue;
                }
            } else {
                $message = $violation->getMessage();
            }

            $propertyPath = $violation->getPropertyPath();
            if ('[' === mb_substr($propertyPath, 0, 1) && ']' === mb_substr($propertyPath, -1, 1)) {
                $propertyPath = trim($propertyPath, '[]');
            }

            $errors[$propertyPath][] = $message;
        }

        return $errors;
    }
}
