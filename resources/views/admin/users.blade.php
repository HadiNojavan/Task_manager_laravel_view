<x-layout title="Users">

    <h1>Users</h1>

    <a href="{{ route('admin.page') }}">Back to Admin Panel</a>

    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif

    @forelse ($users as $user)

        <div>
            <p><strong>{{ $user['name'] }}</strong> — {{ $user['email'] }} — {{ $user['role'] }}</p>

            @if ($role == 'super_admin')
                <form method="POST" action="{{ route('users.destroy', $user['id']) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endif
        </div>

        <hr>

    @empty

        <p>No users found.</p>

    @endforelse

    <p>
        Page {{ $meta['current_page'] }} of {{ $meta['last_page'] }}
    </p>

    @if ($meta['current_page'] > 1)
        <a href="{{ route('users.page', ['page' => $meta['current_page'] - 1]) }}">Previous</a>
    @endif

    @if ($meta['current_page'] < $meta['last_page'])
        <a href="{{ route('users.page', ['page' => $meta['current_page'] + 1]) }}">Next</a>
    @endif

</x-layout>
