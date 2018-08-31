<?php

namespace Artesaos\Defender\Testing;

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;

/**
 * Class PermissionTableSeeder.
 */
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run()
    {
        Permission::unguard();

        Permission::create(['name' => 'user.create', 'readable_name' => '']);
        Permission::create(['name' => 'user.delete', 'readable_name' => '']);
    }
}
