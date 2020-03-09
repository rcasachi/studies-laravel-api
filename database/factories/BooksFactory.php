<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->sentence,
        'publication_year' => (string) $faker->year
    ];
});
