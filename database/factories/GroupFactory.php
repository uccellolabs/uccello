<?php

namespace Uccello\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Uccello\Core\Models\Group;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'workspace_id' => 1,
        ];
    }
}
