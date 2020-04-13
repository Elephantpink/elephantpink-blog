<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use EPink\Blog\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        "name" => "My test category",
        "description" => "My test category description"
    ];
});
