<?php

namespace Tests;

use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function loginAs($role)
    {
        $user = User::where('role', $role)->inRandomOrder()->first();
        if (!$user) throw new NotFound();
        $u = auth()->user();
        if ($u)
            auth()->logout();
        $jwt = auth()->login($user);
        return $jwt;
    }
}
