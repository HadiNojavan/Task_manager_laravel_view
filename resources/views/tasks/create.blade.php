<x-layout title="Create Task">

    <h1>Create Task</h1>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
        </div>

        <div>
            <label>Description:</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Status:</label>
            <select name="status">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div>
            <label>Priority:</label>
            <select name="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <div>
            <label>Category:</label>
            <select name="category_id">
                <option value="">-- None --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}" @selected(old('category_id') == $category['id'])>
                        {{ $category['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Due Date:</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" required>
        </div>

        <button type="submit">Create</button>
    </form>

    <a style="display:block ;margin-top: 15px" href="{{ route('tasks.page') }}">Back to Tasks</a>

</x-layout>
