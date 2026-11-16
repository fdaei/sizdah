<?php

declare(strict_types=1);

namespace App\Policies;

/** 404 monitor shares the (admin-only) redirect permissions. */
final class NotFoundLogPolicy extends BasePolicy
{
    protected function resource(): string
    {
        return 'redirect';
    }
}
