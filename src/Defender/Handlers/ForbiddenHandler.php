<?php

namespace Artesaos\Defender\Handlers;

use Artesaos\Defender\Contracts\ForbiddenHandler as ForbiddenHandlerContract;
use Artesaos\Defender\Exceptions\ForbiddenException;

class ForbiddenHandler implements ForbiddenHandlerContract
{
    public function handle()
    {
        throw new ForbiddenException;
    }
}
