<?php

declare(strict_types=1);

namespace App\Models;

/** Public 403 responses recorded for review, independently from 404s. */
final class ForbiddenLog extends NotFoundLog
{
    protected $table = 'forbidden_logs';
}
