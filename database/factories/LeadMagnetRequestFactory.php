<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LeadMagnetEmailStatus;
use App\Models\LeadMagnetRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadMagnetRequest>
 */
final class LeadMagnetRequestFactory extends Factory
{
    protected $model = LeadMagnetRequest::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'locale' => 'fa',
            'source' => 'article',
            'email_status' => LeadMagnetEmailStatus::Skipped,
        ];
    }
}
