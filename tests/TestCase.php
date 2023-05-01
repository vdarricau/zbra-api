<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, $guard = null): void
    {
        Sanctum::actingAs($user, [], $guard);
        parent::actingAs($user, $guard);
    }
}
