<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Prevent shared caches from retaining reads made inside a transaction. */
final class TransactionalCache
{
    public static function active(): bool
    {
        return DB::transactionLevel() > 0
            && app('db.transactions')->callbackApplicableTransactions()->isNotEmpty();
    }

    public static function invalidate(Model $model, callable $callback): void
    {
        $callback();
        if (self::active()) {
            // Another request may repopulate a cache before this write commits.
            $model->getConnection()->afterCommit($callback);
        }
    }
}
