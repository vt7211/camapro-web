<?php
use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = Role::create([
            'name' => 'Supper Admin',
            'slug' => 'supper',
            'permissions' => [
                'post.create' => true,
                'post.update' => true,
                'post.publish' => true,
            ]
        ]);
        $editor = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'post.update' => true,
                'post.publish' => true,
                'post.create' => true,
            ]
        ]);
        $editor = Role::create([
            'name' => 'Tác Giả',
            'slug' => 'author',
            'permissions' => [
                'post.create' => true,
            ]
        ]);
    }
}
