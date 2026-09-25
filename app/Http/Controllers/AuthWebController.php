<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, ApiClient $apiClient)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $response = $apiClient->client()->post('/api/login', [
                'json' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            session([
                'token' => $data['access_token'],
                'user'  => $data['user'],
            ]);

            $role = $data['user']['role'] ?? null;

            if (in_array($role, ['admin', 'superadmin'])) {
                return redirect()->route('admin.page');
            }

            return redirect()->route('tasks.page');

        } catch (RequestException $e) {
            return back()->withErrors(['email' => 'Invalid email or password']);
        }
    }

    public function logout(Request $request, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->delete('/api/logout', [
                'headers' => ['Authorization' => 'Bearer ' . session('token')],
            ]);
        } catch (RequestException $e) {
            //
        }

        $request->session()->forget(['token', 'user']);
        return redirect()->route('login.page');
    }
}
