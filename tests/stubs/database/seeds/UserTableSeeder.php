<?php

namespace Artesaos\Defender\Testing;

use Illuminate\Database\Seeder;
use Artesaos\Defender\Testing\User;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run()
    {
        User::unguard();

        User::truncate();

        User::create([
            'name' => 'admin',
            'email' => 'admin@localhost.com',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'name' => 'normal',
            'email' => 'normal@localhost.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
