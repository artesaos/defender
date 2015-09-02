<?php

namespace Artesaos\Defender\Handlers;

use Artesaos\Defender\Contracts\ForbiddenHandler;
use Artesaos\Defender\Exceptions\ForbiddenException;

class ForbiddenHandler implements ForbiddenHandler
{
    public function handle()
    {
        throw new ForbiddenException;
    }
}
