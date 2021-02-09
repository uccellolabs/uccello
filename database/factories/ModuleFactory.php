<?php

namespace Uccello\Database\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Uccello\Core\Models\Module;

class ModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'icon' => null,
            'model_class' => null,
            'data' => null
        ];
    }
}
