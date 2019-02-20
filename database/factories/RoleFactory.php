<?php

use Faker\Generator as Faker;

/**
 * Create factory for App\Role model.
 *
 */
$factory->define(App\Role::class, function (Faker $faker, $name) {
    return [
        'name'              => $name,
        'description'       => $faker->text(50),
    ];
});
