<?php

declare(strict_types=1);

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

uses()->beforeEach(function (): void {
    // Subscription tests exercise persistence and delivery, without live DNS.
    // Keep RFC validation so malformed addresses still fail through the real request.
    $this->mock(EmailValidator::class)
        ->shouldReceive('isValid')
        ->andReturnUsing(fn (string $email): bool => (new EmailValidator)->isValid(
            $email,
            new RFCValidation,
        ));
})->in('Feature/Forms/NewsletterSubscriptionTest.php', 'Feature/Forms/LeadMagnetRequestTest.php');
