<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class UserWebController extends Controller
{
    public function index(Request $request, ApiClient $apiClient)
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        $role = session('user')['role'] ?? null;

        if (!in_array($role, ['admin', 'super_admin'])) {
            return redirect()->route('tasks.page');
        }

        try {
            $response = $apiClient->client()->get('/api/users', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'query' => [
                    'page' => $request->query('page', 1),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            $users = $result['data'];
            $meta  = $result['meta'];

            return view('admin.users', compact('users', 'meta', 'role'));

        } catch (RequestException $e) {
            return redirect()->route('admin.page')->withErrors(['api' => 'Failed to load users']);
        }
    }

    public function destroy($id, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->delete("/api/users/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            return redirect()->route('users.page');

        } catch (RequestException $e) {
            return redirect()->route('users.page')->withErrors(['api' => 'Failed to delete user']);
        }
    }
}
