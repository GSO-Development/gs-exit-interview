<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ExitSurvey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ExitSurvey>
 */
class ExitSurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => Str::random(64),
            'access_code' => 'GS-'.strtoupper(Str::random(6)),
            'employee_name' => fake()->name(),
            'employee_email' => fake()->unique()->safeEmail(),
            'designation' => fake()->jobTitle(),
            'department' => 'Finance',
            'company_id' => Company::factory(),
            'sent_by' => User::factory(),
            'expires_at' => now()->addDays(14),
            'status' => 'pending',
            'declaration_accepted' => false,
        ];
    }

    /**
     * Indicate that the survey link has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }

    /**
     * Indicate that the employee has already accepted the declaration.
     */
    public function declarationAccepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'declaration_accepted' => true,
            'declaration_accepted_at' => now(),
            'declaration_accepted_ip' => '127.0.0.1',
        ]);
    }
}
