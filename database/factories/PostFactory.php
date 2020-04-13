<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use EPink\Blog\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        "title" => "My post title",
        "subtitle" => "My post subtitle",
        "excerpt" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor...",
        "thumbnail_url" => "fakeroute.jpg",
        "body" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
                    sunt in culpa qui officia deserunt mollit anim id est laborum.",
        "slug" => "my-post-title",
        "author_id" => 1,
        "publish_date" => date("Y-m-d H:i:s")
    ];
});
