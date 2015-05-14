<?php

namespace spec\Artesaos\Defender;

use ArrayAccess;
use Artesaos\Defender\Contracts\Repositories\PermissionRepository;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use PhpSpec\ObjectBehavior;

/**
 * Class DefenderSpec.
 */
class DefenderSpec extends ObjectBehavior
{
    public function let(Application $app, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->beConstructedWith($app, $roleRepository, $permissionRepository);
    }

    public function it_should_return_a_null_user(ArrayAccess $app, Guard $auth)
    {
        $auth->user()->shouldBeCalled()->willReturn(null);
        $app->offsetGet('auth')->shouldBeCalled()->willReturn($auth);
        $this->getUser()->shouldReturn(null);
    }

    public function it_should_return_a_authenticable_user(ArrayAccess $app, Guard $auth, Authenticatable $user)
    {
        $auth->user()->shouldBeCalled()->willReturn($user);
        $app->offsetGet('auth')->shouldBeCalled()->willReturn($auth);
        $this->getUser()->shouldHaveType('Illuminate\Contracts\Auth\Authenticatable');
    }

    public function it_should_return_false_when_the_given_role_does_not_exists(RoleRepository $roleRepository)
    {
        $roleRepository->findByName('foo')->shouldBeCalled()->willReturn(null);
        $this->roleExists('foo')->shouldReturn(false);
    }

    public function it_should_return_true_when_the_given_role_exists(RoleRepository $roleRepository, $role)
    {
        $role->beADoubleOf('Artesaos\Defender\Role');
        $roleRepository->findByName('foo')->shouldBeCalled()->willReturn($role);
        $this->roleExists('foo')->shouldReturn(true);
    }

    public function it_should_return_false_when_the_given_permission_does_not_exists(PermissionRepository $permissionRepository)
    {
        $permissionRepository->findByName('foo')->shouldBeCalled()->willReturn(null);
        $this->permissionExists('foo')->shouldReturn(false);
    }

    public function it_should_return_true_when_the_given_permission_exists(PermissionRepository $permissionRepository, $permission)
    {
        $permission->beADoubleOf('Artesaos\Defender\Permission');
        $permissionRepository->findByName('foo')->shouldBeCalled()->willReturn($permission);
        $this->permissionExists('foo')->shouldReturn(true);
    }

    public function it_should_return_false_on_can_when_user_is_null(ArrayAccess $app, Guard $auth)
    {
        $auth->user()->shouldBeCalled()->willReturn(null);
        $app->offsetGet('auth')->shouldBeCalled()->willReturn($auth);
        $this->can('permission_name')->shouldReturn(false);
    }

    public function it_should_throw_an_exception_when_the_given_role_already_exists(RoleRepository $roleRepository)
    {
        $roleRepository->create('foo')->shouldBeCalled()->willThrow(new \Exception());
        $this->shouldThrow('\Exception')->duringCreateRole('foo');
    }
}
