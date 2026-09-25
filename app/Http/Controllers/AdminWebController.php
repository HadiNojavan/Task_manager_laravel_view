<?php

namespace App\Http\Controllers;

class AdminWebController extends Controller
{
    public function index()
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        $role = session('user')['role'] ?? null;

        if (!in_array($role, ['admin', 'superadmin'])) {
            return redirect()->route('tasks.page');
        }

        return view('admin.index', compact('role'));
    }
}

