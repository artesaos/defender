<?php

namespace Artesaos\Defender\Exceptions;

class ForbiddenException extends DefenderException
{
    public function __construct($message = 'You don\'t have permission to access this resource')
    {
        parent::__construct($message);
    }
}
