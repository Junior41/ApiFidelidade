<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reward;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reward>
 */
class RewardFactory extends Factory
{
    protected $model = Reward::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $index = 0;
        $rewards = [
            ['name' => 'Suco de Laranja', 'pointsCost' => 5],
            ['name' => '10% de desconto', 'pointsCost' => 10],
            ['name' => 'Almoco especial', 'pointsCost' => 20],
        ];

        $reward = $rewards[$index % count($rewards)];
        $index++;

        return [
            'name' => $reward['name'],
            'pointsCost' => $reward['pointsCost'],
        ];
    }
}
