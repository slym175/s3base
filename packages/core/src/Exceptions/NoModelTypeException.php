<?php

namespace S3base\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class NoModelTypeException extends \Exception
{
    protected $code = SymfonyResponse::HTTP_PRECONDITION_FAILED;
    protected $message = 'No Model Type.';
}
