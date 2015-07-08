<?php

namespace Artesaos\Defender;

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

        return view('defender::javascript', compact('roles', 'permissions'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getRoles()
    {
        return $this->defender->getUser()->roles()->get()->toBase();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getPermissions()
    {
        return $this->defender->getUser()->getPermissions();
    }
}
