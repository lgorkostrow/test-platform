<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractAppInformativeException extends HttpException implements AppInformativeExceptionInterface
{

}
