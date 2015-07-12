<?php

namespace Artesaos\Defender\Commands;

use Illuminate\Console\Command;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Artesaos\Defender\Contracts\User as UserContract;

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
     * User which implements UseContract.
     *
     * @var UserContract
     */
    protected $user;

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
     * @param UserContract   $user
     */
    public function __construct(RoleRepository $roleRepository, UserContract $user)
    {
        parent::__construct();

        $this->roleRepository = $roleRepository;
        $this->user = $user;
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
        $this->info('Permission created successfully');

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
        if ($user = $this->user->findById($userId)) {
            $user->attachRole($role);
            $this->info('Role attached successfully to user');
        } else {
            $this->error('Not possible to attach role. User not found');
        }
    }
}
