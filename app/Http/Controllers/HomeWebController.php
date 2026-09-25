<?php

namespace App\Http\Controllers;

class HomeWebController extends Controller
{
    public function index()

    {
        if (session()->has('token')) {
            $role = session('user')['role'] ?? null;

            if (in_array($role, ['admin', 'super_admin'])) {
                return redirect()->route('admin.page');
            }

            return redirect()->route('tasks.page');
        }

        return view('home');
    }
}

