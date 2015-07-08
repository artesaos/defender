<?php

namespace Artesaos\Defender\Testing;

use Artesaos\Defender\Role;
use Illuminate\Database\Seeder;

/**
 * Class RoleTableSeeder.
 */
class RoleTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run()
    {
        Role::unguard();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'superuser']);
        Role::create(['name' => 'noaccess']);
    }
}
