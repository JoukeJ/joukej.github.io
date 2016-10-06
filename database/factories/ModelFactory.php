<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(\App\Models\User::class, function ($faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'email' => $faker->email,
        'language' => $faker->randomElement(['nl', 'en']),
        'timezone' => $faker->randomElement(['Europe/Amsterdam', 'GMT']),
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\Role::class, function ($faker) {
    return [
        'name' => strtolower($faker->name),
        'display_name' => $faker->name,
        'description' => $faker->text(100)
    ];
});

$factory->define(\App\Models\Permission::class, function ($faker) {
    return [
        'name' => strtolower($faker->name),
        'display_name' => $faker->name,
        'description' => $faker->text(100)
    ];
});

$factory->define(\App\TTC\Models\Survey::class, function ($faker) {
    return [
        'user_id' => rand(1, 8),
        'language' => $faker->randomElement(['es', 'en']),
        'name' => $faker->sentence(3),
        'priority' => rand(1, 255),
        'status' => $faker->randomElement(['open', 'closed', 'cancelled', 'draft', 'draft', 'draft', 'draft', 'draft']),
        // lean towards draft for testing purposes
        'start_date' => $faker->dateTimeBetween('-30 days', '-5 days'),
        'end_date' => $faker->dateTimeBetween('+5 days', '+ 30 days'),
        'identifier' => str_random(8),
    ];
});

$factory->define(\App\TTC\Models\Survey\Matchgroup::class, function ($faker) {
    return [
        'survey_id' => null,
        'name' => $faker->name
    ];
});

$factory->define(\App\TTC\Models\Survey\Matchrule::class, function ($faker) {
    return [
        'matchgroup_id' => null,
        'attribute' => $faker->randomElement(['age', 'country', 'gender']),
        'operator' => $faker->randomElement(['==', '!=', '>', '>=', '<', '<=']),
        'values' => $faker->randomDigit
    ];
});

$factory->define(\App\TTC\Models\Survey\Repeat::class, function ($faker) {
    return [
        'survey_id' => null,
        'interval' => $faker->randomElement(['daily', 'weekly', 'monthly']),
        'absolute_end_date' => $faker->dateTimeBetween('now', '+3 years')
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity::class, function ($faker) {
    return [
        'survey_id' => $faker->numberBetween(0, 101),
        'identifier' => str_random(16),
        'order' => $faker->numberBetween(0, 255)
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Question\Open::class, function ($faker) {
    return [
        'question' => $faker->sentence(3),
        'description' => $faker->sentence(8),
        'required' => rand(0, 1),
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Question\Text::class, function ($faker) {
    return [
        'question' => $faker->sentence(3),
        'description' => $faker->sentence(8),
        'required' => rand(0, 1),
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Question\Radio::class, function ($faker) {
    return [
        'question' => $faker->sentence(3),
        'description' => $faker->sentence(8),
        'required' => rand(0, 1),
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Question\Checkbox::class, function ($faker) {
    return [
        'question' => $faker->sentence(5),
        'description' => $faker->sentence(5),
        'required' => rand(0, 1),
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Question\Image::class, function ($faker) {
    return [
        'question' => $faker->sentence(3),
        'description' => $faker->sentence(5),
        'required' => rand(0, 1),
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Info\Image::class, function ($faker) {
    return [
        'identifier' => str_random(8),
        'description' => $faker->sentence(3),
        'path' => '',
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Info\Video::class, function ($faker) {
    return [
        'description' => $faker->sentence(5),
        'service' => $faker->randomElement(['youtube', 'vimeo']),
        'url' => $faker->url,
    ];
});

$factory->define(\App\TTC\Models\Survey\Entity\Logic\Skip::class, function ($faker) {
    return [];
});


$factory->define(\App\TTC\Models\Survey\Entity\Option::class, function ($faker) {
    return [
        'entity_id' => null,
        'entity_type' => null,
        'name' => 'options',
        'value' => substr($faker->sentence(rand(1, 4)), 0, -1) . '?'
    ];
});

$factory->define(\App\TTC\Models\Survey\Answer::class, function ($faker) {
    return [
        'profile_id' => null,
        'survey_id' => null,
        'answer' => $faker->randomNumber
    ];
});

$factory->define(\App\TTC\Models\Language::class, function ($faker) {
    return [
        'name' => $faker->word,
        'iso' => $faker->lexify('??')
    ];
});

$factory->define(\App\TTC\Models\Country::class, function ($faker) {
    return [
        'iso' => $faker->lexify('??')
    ];
});

$factory->define(\App\TTC\Models\Profile::class, function ($faker) {
    return [
        'language_id' => null,
        'identifier' => str_random(8),
        'phonenumber' => $faker->numberBetween(1000000000, 9999999999),
        'name' => $faker->name,
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->date,
        'geo_country_id' => null,
        'geo_city' => $faker->name,
        'geo_lat' => $faker->latitude,
        'geo_lng' => $faker->longitude,
        'device' => $faker->randomElement(['feature', 'smart']),
    ];
});

$factory->define(\App\TTC\Models\Profile\Survey::class, function ($faker) {
    return [
        'profile_id' => null,
        'survey_id' => null,
        'entity_id' => null,
        'status' => $faker->randomElement(['progress', 'completed', 'abandoned']),
        'previous' => $faker->randomElement([0, 1])
    ];
});

$factory->define(\App\TTC\Models\Survey\Repeat::class, function ($faker) {
    return [
        'absolute_end_date' => $faker->dateTimeBetween('+1 weeks', '+10 weeks'),
        'interval' => 'week'
    ];
});
