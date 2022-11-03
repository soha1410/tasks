<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function lists()
    {
        $user = auth()->user();
        if ($user->role != 'admin') return response('Access Denied', 403);
        return UserResource::collection(User::all());
    }
    public function makeAdmin(User $user)
    {
        $admin = auth()->user();
        if ($admin->role != 'admin') return response('Access Denied', 403);

        $user->role = 'admin';
        $user->update();
    }
}
