<?php

namespace Artesaos\Defender\Handlers;

use Artesaos\Defender\Exceptions\ForbiddenException;
use Artesaos\Defender\Contracts\ForbiddenHandler as ForbiddenHandlerContract;

class ForbiddenHandler implements HandlerInterface
{
    public function handle()
    {
        throw new ForbiddenException;
    }
}
