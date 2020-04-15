<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Controllers\LoginController;

class NovaLoginController extends LoginController
{

    protected function attemptLogin(Request $request)
    {
        if (!$this->authorizeable($request))
            return false;

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    private function authorizeable(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user)
            return false;

        if ($user['area'] !== null) {
            return Str::contains($request->root(), $user['domain_access']);
        }

        return true;
    }

}
