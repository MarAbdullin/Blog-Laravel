<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;





class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        Category::factory()->count(12)->create();
        Post::factory()->count(50)->create();
        Tag::factory()->count(100)->create();
        Comment::factory()->count(200)->create();
    }
}
