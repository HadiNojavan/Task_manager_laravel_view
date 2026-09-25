<x-layout title="Assign Task">

    <h1>Assign Task</h1>
    <a href="{{ route('admin.page') }}">Back to Admin Panel</a>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('tasks.assign.web.store') }}">
        @csrf

        <h3>Select Task:</h3>

        <select name="task_id" required>
            <option value="">-- Select Task --</option>

            @foreach ($tasks as $task)
                <option value="{{ $task['id'] }}">
                    #{{ $task['id'] }} - {{ $task['title'] }}
                </option>
            @endforeach
        </select>

        <h3>Select Users:</h3>

        @forelse ($users as $user)
            <div>
                <label>
                    <input
                        type="checkbox"
                        name="user_ids[]"
                        value="{{ $user['id'] }}"
                    >

                    {{ $user['name'] }} - {{ $user['email'] }}
                </label>
            </div>
        @empty
            <p>No users found.</p>
        @endforelse

        <br>

        <button type="submit">Assign Task</button>
    </form>

</x-layout>
