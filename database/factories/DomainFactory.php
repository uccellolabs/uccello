<?php

namespace Uccello\Database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence,
        ];
    }
}
