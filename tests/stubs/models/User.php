<?php

namespace Artesaos\Defender\Testing;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Traits\HasDefender;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User.
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasDefender;

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTable()
    {
        return config('auth.table', 'users');
    }
}
