<?php

declare(strict_types=1);

use App\Filament\Resources\ForbiddenLogResource\Pages\ListForbiddenLogs;
use App\Models\ForbiddenLog;
use App\Models\NotFoundLog;
use App\Models\Redirect;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Livewire\Livewire;

it('records public forbidden responses independently and creates active redirects for selected paths', function (): void {
    foreach (['fa/blocked-one', 'fa/blocked-one', 'fa/blocked-two'] as $path) {
        $request = Request::create('http://localhost/'.$path, 'GET');
        $response = app(\Illuminate\Contracts\Debug\ExceptionHandler::class)->render($request, new AccessDeniedHttpException());
        expect($response->getStatusCode())->toBe(403);
    }

    $first = ForbiddenLog::query()->where('path', 'fa/blocked-one')->firstOrFail();
    $second = ForbiddenLog::query()->where('path', 'fa/blocked-two')->firstOrFail();
    expect($first->hits)->toBe(2)->and(NotFoundLog::query()->count())->toBe(0);

    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    Livewire::test(ListForbiddenLogs::class)
        ->callTableBulkAction('redirect_all', [$first, $second], ['destination' => 'fa/services', 'status' => 301])
        ->assertHasNoTableBulkActionErrors();

    expect(ForbiddenLog::query()->count())->toBe(0)
        ->and(Redirect::query()->where('origin', '403')->where('is_active', true)->count())->toBe(2);
    $this->get('/fa/blocked-one')->assertStatus(301)->assertRedirect(url('/fa/services'));
});
