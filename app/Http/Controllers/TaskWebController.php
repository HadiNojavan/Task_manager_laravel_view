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

        $role = session('user')['role'] ?? null;

        if (in_array($role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.page');
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

    public function trashed(Request $request, ApiClient $apiClient)
{
    if (!session()->has('token')) {
        return redirect()->route('login.page');
    }

    try {
        $response = $apiClient->client()->get('/api/tasks/trashed', [
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

        $role = session('user')['role'] ?? null;

        return view('admin.trashed-tasks', compact('tasks', 'meta', 'role'));

    } catch (RequestException $e) {
        return redirect()->route('admin.page')->withErrors(['api' => 'Failed to load trashed tasks']);
    }
}

    public function restore($id, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->patch("/api/tasks/{$id}/restore", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            return redirect()->route('tasks.trashed.page');

        } catch (RequestException $e) {
            return redirect()->route('tasks.trashed.page')->withErrors(['api' => 'Failed to restore task']);
        }
    }

    public function forceDelete($id, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->delete("/api/tasks/{$id}/force-delete", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            return redirect()->route('tasks.trashed.page');

        } catch (RequestException $e) {
            return redirect()->route('tasks.trashed.page')->withErrors(['api' => 'Failed to permanently delete task']);
        }
    }

    public function assignPage(ApiClient $apiClient)
    {

        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        try {
            $response = $apiClient->client()->get('/api/tasks/assign-data', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            $tasks = $result['tasks'] ?? [];
            $users = $result['users'] ?? [];

            return view('tasks.assign', compact('tasks', 'users'));

        } catch (RequestException $e) {
            return redirect()->route('admin.page')
                ->withErrors(['api' => 'Failed to load tasks or users']);
        }
    }

    public function assign(Request $request, ApiClient $apiClient)
    {
        $request->validate([
            'task_id' => ['required', 'integer'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer'],
        ]);


        try {
            $apiClient->client()->post("/api/tasks/{$request->task_id}/assign", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
                'json' => [
                    'user_ids' => $request->user_ids,
                ],
            ]);

            return redirect()->route('admin.page')
                ->with('success', 'Task assigned successfully');

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Failed to assign task'];

            return back()->withErrors([
                'api' => $message['message'] ?? 'Failed to assign task'
            ])->withInput();
        }
    }

    public function unassignPage(Request $request, ApiClient $apiClient)
    {
        if (!session()->has('token')) {
            return redirect()->route('login.page');
        }

        try {
            $response = $apiClient->client()->get('/api/tasks/assign-data', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('token'),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            $tasks = $result['tasks'] ?? [];

            $assignedUsers = [];
            $selectedTask = null;

            if ($request->filled('task_id')) {

                $taskId = $request->input('task_id');

                $taskResponse = $apiClient->client()->get("/api/tasks/{$taskId}", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . session('token'),
                    ],
                ]);

                $taskResult = json_decode($taskResponse->getBody(), true);

                $selectedTask = $taskResult['data'] ?? null;
                $assignedUsers = $selectedTask['assigned_users'] ?? [];
            }

            return view('tasks.unassign', compact(
                'tasks',
                'selectedTask',
                'assignedUsers'
            ));

        } catch (RequestException $e) {
            return redirect()->route('admin.page')
                ->withErrors(['api' => 'Failed to load task or assigned users']);
        }
    }

    public function unassign($taskId, $userId, ApiClient $apiClient)
    {
        try {
            $apiClient->client()->delete(
                "/api/tasks/{$taskId}/unassign/{$userId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . session('token'),
                    ],
                ]
            );

            return redirect()
                ->route('tasks.unassign.page', ['task_id' => $taskId])
                ->with('success', 'User unassigned successfully');

        } catch (RequestException $e) {
            $message = $e->getResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : ['message' => 'Failed to unassign user'];

            return back()->withErrors([
                'api' => $message['message'] ?? 'Failed to unassign user'
            ]);
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
