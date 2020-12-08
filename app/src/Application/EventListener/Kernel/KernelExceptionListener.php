<?php

declare(strict_types=1);

namespace App\Application\EventListener\Kernel;

use App\Application\Exception\AppInformativeExceptionInterface;
use App\Application\Http\Exception\ValidationHttpException;
use App\Application\Http\Response\AppInformativeExceptionResponse;
use App\Application\Utils\ValidatorUtils;
use Exception;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\FOSRestBundle;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class KernelExceptionListener
{
    private NormalizerInterface $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->attributes->get(FOSRestBundle::ZONE_ATTRIBUTE, true)) {
            return;
        }

        if ($this->isApiRequest($event->getRequest())) {
            $this->handleException($event, $event->getThrowable());
        }
    }

    protected function isApiRequest(Request $request): bool
    {
        return $request->headers->get('Content-Type') === 'application/json'
            || strpos($request->getPathInfo(), '/api/') === 0;
    }

    protected function handleException(ExceptionEvent $event, Throwable $exception): void
    {
        if ($exception instanceof HttpException) {
            switch (true) {
                case $exception instanceof ValidationHttpException:
                    $message = $this->formatValidationErrors($exception->getErrors());
                    break;
                case $exception instanceof InvalidParameterException:
                    $message = $this->formatValidationErrors(
                        $this->formatParamFetcherErrors($exception)
                    );
                    break;
                case $exception instanceof AppInformativeExceptionInterface:
                    $message = new AppInformativeExceptionResponse(
                        $exception->getMessage(),
                        $this->normalizer->normalize($exception->getInformation())
                    );
                    break;
                default:
                    $message = ['_system' => str_replace(' ', '_', mb_strtoupper($exception->getMessage()))];
                    break;
            }

            $event->setResponse(new JsonResponse(['errors' => $message], $exception->getStatusCode()));
        } elseif ($exception instanceof RuntimeException) {
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
                    $message = $constraint::getErrorName($violation->getCode());
                } catch (Exception $e) {
                    continue;
                }
            } else {
                $message = $violation->getMessage();
            }

            $propertyPath = $violation->getPropertyPath();
            if (mb_strpos($propertyPath, '[') === 0 && mb_substr($propertyPath, -1, 1) === ']') {
                $propertyPath = trim($propertyPath, '[]');
            }

            $errors[$propertyPath][] = $message;
        }

        return $errors;
    }

    private function formatParamFetcherErrors(InvalidParameterException $exception): ConstraintViolationList
    {
        return new ConstraintViolationList(array_map(static function (ConstraintViolation $item) use ($exception) {
            return ValidatorUtils::rebuildViolationWithPropertyPath($item, $exception->getParameter()->getName());
        }, (array)$exception->getViolations()->getIterator()));
    }
}
