<x-layout title="Admin Panel">

    <h1>Admin Panel</h1>

    <p>Role: {{ $role }}</p>

    <ul>
        <li><a href="{{ route('users.page') }}">Users</a></li>
        @if($role=='admin')
            <li><a href="{{ route('tasks.trashed.page') }}">Restore Task</a></li>
        @endif
        <li><a href="{{ route('tasks.assign.page') }}">Assign Task</a></li>
            <a href="{{ route('tasks.unassign.page') }}">Unassign Task</a>

        @if ($role == 'super_admin')
            <li><a href="{{ route('admins.create.page') }}">Add Admin</a></li>
            <a href="{{ route('tasks.trashed.page') }}">Force Delete Task or restore</a>
            <li><a href="{{ route('users.page') }}">Delete User</a></li>
        @endif
    </ul>

</x-layout>
