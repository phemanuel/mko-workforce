<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'ni_number' => strtoupper(fake()->bothify('??######?')),
            'date_of_birth' => fake()->date(),
            'status' => 'approved',
        ];
    }
}
