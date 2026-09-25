<x-layout title="Edit Task">

    <h1>Edit Task</h1>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('tasks.update', $task['id']) }}">
        @csrf
        @method('PATCH')

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title', $task['title']) }}" required>
        </div>

        <div>
            <label>Description:</label>
            <textarea name="description">{{ old('description', $task['description']) }}</textarea>
        </div>

        <div>
            <label>Status:</label>
            <select name="status">
                <option value="pending" @selected(old('status', $task['status']) == 'pending')>Pending</option>
                <option value="completed" @selected(old('status', $task['status']) == 'completed')>Completed</option>
            </select>
        </div>

        <div>
            <label>Priority:</label>
            <select name="priority">
                <option value="low" @selected(old('priority', $task['priority']) == 'low')>Low</option>
                <option value="medium" @selected(old('priority', $task['priority']) == 'medium')>Medium</option>
                <option value="high" @selected(old('priority', $task['priority']) == 'high')>High</option>
            </select>
        </div>

        <div>
            <label>Category:</label>
            <select name="category_id">
                <option value="">-- None --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}" @selected(old('category_id', $task['category']['id'] ?? null) == $category['id'])>
                        {{ $category['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Due Date:</label>
            <input type="date" name="due_date" value="{{ old('due_date', \Illuminate\Support\Str::before($task['due_date'], 'T')) }}" required>
        </div>

        <button style="display:block ;margin-top: 15px" type="submit">Update</button>
    </form>


    <form style="display:block ; border-color: #f61500 ;margin-top: 15px" method="POST" action="{{ route('tasks.destroy', $task['id']) }}" onsubmit="return confirm('Are you sure you want to delete this task?')">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Task</button>
    </form>

    <a style="display:block ; color: #f8b803 ;margin-top: 15px" href="{{ route('tasks.page') }}">Back to Tasks</a>

</x-layout>
