<x-layout title="Deleted Tasks">

    <h1>Deleted Tasks</h1>
    <a href="{{ route('admin.page') }}">Back to Admin Panel</a>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @forelse ($tasks as $task)

        <div>
            <h3>#{{ $task['id'] }} - {{ $task['title'] }}</h3>

            <p>{{ $task['description'] }}</p>

            <p>
                Status: {{ $task['status'] }} |
                Priority: {{ $task['priority'] }} |
                Deleted At: {{ $task['deleted_at'] ?? '-' }}
            </p>

            <form method="POST"
                  action="{{ route('tasks.restore.web', $task['id']) }}"
                  style="display:inline;">
                @csrf
                @method('PATCH')

                <button type="submit">
                    Restore
                </button>
            </form>

            @if ($role == 'super_admin')
                <form method="POST"
                      action="{{ route('tasks.force-delete.web', $task['id']) }}"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Force Delete
                    </button>
                </form>
            @endif
        </div>

        <hr>

    @empty

        <p>No deleted tasks found.</p>

    @endforelse

    @if ($meta['current_page'] < $meta['last_page'])
        <a href="{{ route('tasks.trashed.page', ['page' => $meta['current_page'] + 1]) }}">
            Next
        </a>
    @endif

    @if ($meta['current_page'] > 1)
        <a href="{{ route('tasks.trashed.page', ['page' => $meta['current_page'] - 1]) }}">
            Previous
        </a>
    @endif

</x-layout>
