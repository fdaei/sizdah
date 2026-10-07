<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Grants the new `lead_magnet_request` permissions to the existing roles, so
 * a live install gets the admin screen without re-running
 * RolePermissionSeeder (which grants the same set on a fresh one).
 */
return new class extends Migration
{
    private const ACTIONS = [
        'view_any', 'view', 'create', 'update', 'update_any', 'delete', 'delete_any', 'restore',
    ];

    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $names = array_map(fn (string $action): string => "{$action}_lead_magnet_request", self::ACTIONS);

        foreach ($names as $name) {
            Permission::findOrCreate($name, 'web');
        }

        Role::query()->where('name', 'admin')->first()?->givePermissionTo($names);
        Role::query()->where('name', 'editor')->first()?->givePermissionTo(
            array_values(array_filter($names, fn (string $name): bool => ! str_starts_with($name, 'delete_any_'))),
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::query()->where('name', 'like', '%_lead_magnet_request')->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
