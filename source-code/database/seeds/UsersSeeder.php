<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = Role::where('slug', 'author')->first();
        $admin = Role::where('slug', 'admin')->first();
        $supper = Role::where('slug', 'supper')->first();

        $user1 = User::create([
            'username' => 'vt7211',
            'firstname' => 'Tấn',
            'lastname' => 'Đinh',
            'phone' => '0909979367',
            'level' => 4,
            'email' => 'vantan7211@gmail.com',
            'password' => bcrypt('123456')
        ]);
        $user1->roles()->attach($supper);
    }
}
