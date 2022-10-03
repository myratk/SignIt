<?php

namespace Database\Factories;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{
    protected $model = Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name(),
            'comments'=>$this->faker->realText(500)
        ];
    }
}
