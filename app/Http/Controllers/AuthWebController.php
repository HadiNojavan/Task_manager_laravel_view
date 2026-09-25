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
            return redirect()->route('home.page');


        } catch (RequestException $e) {
            return back()->withErrors(['email' => 'Invalid email or password']);
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request, ApiClient $apiClient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = $apiClient->client()->post('/api/register', [
                'json' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            session([
                'token' => $data['access_token'],
                'user'  => $data['user'],
            ]);

            return $this->redirectByRole($data['user']['role'] ?? null);

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Registration failed'];

            return back()->withErrors(['api' => $message['message'] ?? 'Registration failed'])->withInput();
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

    private function redirectByRole(?string $role)
    {
        if (in_array($role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.page');
        }

        return redirect()->route('tasks.page');
    }
}
