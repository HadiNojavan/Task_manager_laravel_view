<x-layout title="Unassign User">

    <h1>Unassign User from Task</h1>
    <a href="{{ route('admin.page') }}">Back to Admin Panel</a>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('tasks.unassign.page') }}">

        <label>Select Task:</label>

        <select name="task_id" onchange="this.form.submit()" required>
            <option value="">-- Select Task --</option>

            @foreach ($tasks as $task)
                <option
                    value="{{ $task['id'] }}"
                    @selected($selectedTask && $selectedTask['id'] == $task['id'])
                >
                    #{{ $task['id'] }} - {{ $task['title'] }}
                </option>
            @endforeach
        </select>

    </form>

    @if ($selectedTask)

        <h2>
            Task #{{ $selectedTask['id'] }}:
            {{ $selectedTask['title'] }}
        </h2>

        <h3>Assigned Users:</h3>

        @forelse ($assignedUsers as $user)

            <div>
                <span>
                    {{ $user['name'] }} - {{ $user['email'] }}
                </span>

                <form
                    method="POST"
                    action="{{ route('tasks.unassign.web', [
                        'task' => $selectedTask['id'],
                        'user' => $user['id']
                    ]) }}"
                    style="display:inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Unassign
                    </button>
                </form>
            </div>

        @empty

            <p>No users are assigned to this task.</p>

        @endforelse

    @endif

</x-layout>
