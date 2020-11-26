<?php

declare(strict_types=1);

namespace App\Application\EventListener\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestFormDataTransformer
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_POST)) {
            return;
        }

        if (false === strpos($request->headers->get('CONTENT_TYPE'), 'multipart/form-data')) {
            return;
        }

        if (!$request->request->has('data')) {
            throw new BadRequestHttpException('Field data is required');
        }

        $data = json_decode($request->request->get('data'), true);

        if ($request->files->count()) {
            $files = $request->files->all();
            array_walk_recursive($data, function (&$value, $key) use (&$files) {
                if (in_array($value, array_keys($files), true)) {
                    $key = $value;
                    $value = $files[$value];

                    unset($files[$key]);
                }
            });

            if (!empty($files)) {
                throw new BadRequestHttpException('Field data is required');
                throw new FilesNotMappedException(array_keys($files));
            }
        }

        $request->request->replace($data);
        $request->headers->set('CONTENT_TYPE', 'application/json');
    }
}
