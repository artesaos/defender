<?php

namespace Artesaos\Defender\Commands;

use Illuminate\Console\Command;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Artesaos\Defender\Contracts\Repositories\UserRepository;

/**
 * Class MakeRole.
 */
class MakeRole extends Command
{
    /**
     * Defender Roles Repository.
     *
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * User which implements UserRepository.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defender:make:role
                            {name : Name of the role}
                            {--user= : User id. Attach role to user with the provided id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a role';

    /**
     * Create a new command instance.
     *
     * @param RoleRepository $roleRepository
     * @param UserRepository $userRepository
     */
    public function __construct(RoleRepository $roleRepository, UserRepository $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        $roleName = $this->argument('name');
        $userId = $this->option('user');

        $role = $this->createRole($roleName);

        if ($userId) {
            $this->attachRoleToUser($role, $userId);
        }
    }

    /**
     * Create role.
     *
     * @param string $roleName
     *
     * @return \Artesaos\Defender\Role
     */
    protected function createRole($roleName)
    {
        // No need to check is_null($role) as create() throwsException
        $role = $this->roleRepository->create($roleName);

        $this->info('Role created successfully');

        return $role;
    }

    /**
     * Attach role to user.
     *
     * @param \Artesaos\Defender\Role $role
     * @param int                     $userId
     */
    protected function attachRoleToUser($role, $userId)
    {
        // Check if user exists
        if ($user = $this->userRepository->findById($userId)) {
            $user->attachRole($role);
            $this->info('Role attached successfully to user');
        } else {
            $this->error('Not possible to attach role. User not found');
        }
    }
}
