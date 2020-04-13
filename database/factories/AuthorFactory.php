<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \EPink\Blog\Models\Author;
use Faker\Generator as Faker;

$factory->define(\EPink\Blog\Models\Author::class, function (Faker $faker) {
    return [
        "name" => "My test author",
        "email" => "author@mail.com",
        "password" => "mypassword"
    ];
});

