<?php

namespace Artesaos\Defender\Exceptions;

/**
 * Class ForbiddenException.
 */
class ForbiddenException extends DefenderException
{
    /**
     * @param string $message
     */
    public function __construct($message = 'You don\'t have permission to access this resource')
    {
        parent::__construct($message);
    }
}
