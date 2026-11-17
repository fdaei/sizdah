<?php

declare(strict_types=1);

namespace App\Policies;

/** Auto-link rules are SEO configuration: they share the (admin-only) redirect permissions. */
final class InternalLinkPolicy extends BasePolicy
{
    protected function resource(): string
    {
        return 'redirect';
    }
}
