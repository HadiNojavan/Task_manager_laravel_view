<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class TaskWebController extends Controller
{
    public function index(Request $request, ApiClient $apiClient)
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        try {
            $response = $apiClient->client()->get('/api/tasks', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'query' => [
                    'page' => $request->query('page', 1),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            $tasks = $result['data'];
            $meta  = $result['meta'];

            return view('tasks.index', compact('tasks', 'meta'));

        } catch (RequestException $e) {
            return redirect()->route('login.page')->withErrors(['api' => 'Session expired, please log in again']);
        }
    }

    public function create(ApiClient $apiClient)
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        $categories = $this->getCategories($apiClient);

        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->post('/api/tasks', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'json' => $request->only(['title', 'description', 'status', 'priority', 'due_date', 'category_id']),
            ]);

            return redirect()->route('tasks.page');

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Failed to create task'];

            return back()->withErrors(['api' => $message['message'] ?? 'Failed to create task'])->withInput();
        }
    }

    public function edit($id, ApiClient $apiClient)
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        try {
            $response = $apiClient->client()->get("/api/tasks/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            $task = $result['data'];

            $categories = $this->getCategories($apiClient);

            return view('tasks.edit', compact('task', 'categories'));

        } catch (RequestException $e) {
            return redirect()->route('tasks.page')->withErrors(['api' => 'Task not found or access denied']);
        }
    }

    public function update(Request $request, $id, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->patch("/api/tasks/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'json' => $request->only(['title', 'description', 'status', 'priority', 'due_date', 'category_id']),
            ]);

            return redirect()->route('tasks.page');

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Failed to update task'];

            return back()->withErrors(['api' => $message['message'] ?? 'Failed to update task'])->withInput();
        }
    }

    public function destroy($id, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->delete("/api/tasks/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            return redirect()->route('tasks.page');

        } catch (RequestException $e) {
            return redirect()->route('tasks.page')->withErrors(['api' => 'Failed to delete task']);
        }
    }

    private function getCategories(ApiClient $apiClient): array
    {
        try {
            $response = $apiClient->client()->get('/api/categories', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            return [];
        }
    }
}
