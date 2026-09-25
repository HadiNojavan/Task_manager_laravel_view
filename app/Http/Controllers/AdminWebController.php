<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class AdminWebController extends Controller
{
    public function index()
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        $role = session('user')['role'] ?? null;

        if (!in_array($role, ['admin', 'super_admin'])) {
            return redirect()->route('tasks.page');
        }

        return view('admin.index', compact('role'));
    }

    public function create()
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('admin.page');
        }

        return view('admin.create-admin');
    }

    public function store(Request $request, ApiClient $apiClient)
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->route('admin.page');
        }

        try {
            $apiClient->client()->post('/api/admins', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'json' => $request->only(['name', 'email', 'password', 'password_confirmation']),
            ]);

            return redirect()->route('admin.page');

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Failed to create admin'];

            return back()->withErrors(['api' => $message['message'] ?? 'Failed to create admin'])->withInput();
        }
    }

    private function isSuperAdmin(): bool
    {
        if (!session()->has('token')) {
            return false;
        }

        return (session('user')['role'] ?? null) == 'super_admin';
    }
}
