<?php

declare(strict_types=1);

namespace App\Policies;

/** The 403 monitor uses the same permissions as redirects. */
final class ForbiddenLogPolicy extends BasePolicy
{
    protected function resource(): string
    {
        return 'redirect';
    }
}
