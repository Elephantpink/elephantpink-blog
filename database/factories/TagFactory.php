<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use EPink\Blog\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        "name" => "My tag name",
        "description" => "My tag description"
    ];
});
