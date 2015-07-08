<?php

namespace Artesaos\Defender\Testing;

use Illuminate\Database\Seeder;

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
