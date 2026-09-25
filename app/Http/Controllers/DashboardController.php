<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        $role = session('user')['role'] ?? null;

        return view('dashboard.index', compact('role'));
    }
}
