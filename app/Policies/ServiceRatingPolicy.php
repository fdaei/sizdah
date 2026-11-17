<?php

declare(strict_types=1);

namespace App\Policies;

/** Rating moderation shares the testimonial permissions (both are visitor reviews). */
final class ServiceRatingPolicy extends BasePolicy
{
    protected function resource(): string
    {
        return 'testimonial';
    }
}
