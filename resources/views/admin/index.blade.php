<x-layout title="Admin Panel">

    <h1>Admin Panel</h1>

    <ul>
        <li><a href="{{ route('users.page') }}">Users</a></li>
        <li><a href="{{ route('tasks.restore.page') }}">Restore Task</a></li>
        <li><a href="{{ route('tasks.assign.page') }}">Assign Task</a></li>
        <li><a href="{{ route('tasks.unassign.page') }}">Unassign Task</a></li>

        @if ($role == 'superadmin')
            <li><a href="{{ route('admins.create.page') }}">Add Admin</a></li>
            <li><a href="{{ route('tasks.force-delete.page') }}">Force Delete</a></li>
            <li><a href="{{ route('users.delete.page') }}">Delete User</a></li>
        @endif
    </ul>

</x-layout>
