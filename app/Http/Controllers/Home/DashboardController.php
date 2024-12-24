<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function dashboard()
    {


        $user = auth()->user();
        // dd($user);
        $role = $user->roles()->first();
        return Inertia::render('Dashboard', [
            'role' => $role ? $role->name : 'No role assigned',
        ]);
    }
}
