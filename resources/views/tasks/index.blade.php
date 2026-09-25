<x-layout title="Tasks">

    <div style="display: flex; justify-content: space-between;">

        <a style="color: #f8b803" href="{{ route('tasks.create') }}">+ Create Task</a>


    </div>

    <h1>My Tasks</h1>

    @forelse ($tasks as $task)

        <div>
            <h2>
                <a style="color: #ff4433" href="{{ route('tasks.edit', $task['id']) }}">
                    {{ $task['title'] }}
                </a>
            </h2>

            <p>Status: {{ $task['status'] }}</p>
            <p>Priority: {{ $task['priority'] }}</p>
            <p>Description: {{ $task['description'] }}</p>

            @if (!empty($task['category']))
                <p>Category: {{ $task['category'] }}</p>
            @endif
        </div>

        <hr>

    @empty

        <p>No tasks available.</p>

    @endforelse

    <p>
        Page {{ $meta['current_page'] }} of {{ $meta['last_page'] }}
    </p>

    @if ($meta['current_page'] > 1)
        <a href="{{ route('tasks.page', ['page' => $meta['current_page'] - 1]) }}">
            Previous
        </a>
    @endif

    @if ($meta['current_page'] < $meta['last_page'])
        <a style="color: aqua" href="{{ route('tasks.page', ['page' => $meta['current_page'] + 1]) }}">
            Next
        </a>
    @endif

</x-layout>
