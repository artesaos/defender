<?php

namespace Artesaos\Defender;

use Artesaos\Defender\Traits\HasDefender;
use Artesaos\Defender\Contracts\Javascript as JavascriptContract;

/**
 * Class Javascript.
 */
class Javascript implements JavascriptContract
{
    /**
     * @var Defender
     */
    protected $defender;

    /**
     * @param Defender $defender
     */
    public function __construct(Defender $defender)
    {
        $this->defender = $defender;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();

        return view('defender::javascript', [
            'roles' => $roles ? $roles->pluck('name')->toJson() : '[]',
            'permissions' => $permissions ? $permissions->pluck('name')->toJson() : '[]',
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getRoles()
    {
        $user = $this->defender->getUser();

        $roles = $user ? $user->roles()->get()->toBase() : null;

        return $roles;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getPermissions()
    {
        /** @var HasDefender $user */
        $user = $this->defender->getUser();

        $permissions = $user ? $user->getAllPermissions() : null;

        return $permissions;
    }
}
