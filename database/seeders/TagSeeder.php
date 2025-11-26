<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $tags = Tag::all();

        // Add Tag
        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach(
                // to each article, and randomly take the ID of 0 ~ 5 Tag
                $tags->random(random_int(0, 5))->pluck('id')->toArray()
            );
        });
    }
}
