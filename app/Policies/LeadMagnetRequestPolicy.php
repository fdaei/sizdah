<?php

declare(strict_types=1);

namespace App\Policies;

final class LeadMagnetRequestPolicy extends BasePolicy
{
    protected function resource(): string
    {
        return 'lead_magnet_request';
    }
}
