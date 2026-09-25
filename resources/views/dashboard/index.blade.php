<x-layout title="Dashboard">

    <h1>Admin Dashboard</h1>

    <p>Your role: {{ $role }}</p>

    <ul>
        @if (in_array($role, ['admin', 'superadmin']))
            <li><a href="{{ route('users.page') }}">View Users</a></li>
            <li><a href="{{ route('tasks.restore.page') }}">Restore Task</a></li>
            <li><a href="{{ route('tasks.assign.page') }}">Assign Task</a></li>
            <li><a href="{{ route('tasks.unassign.page') }}">Unassign Task</a></li>
        @endif

        @if ($role == 'superadmin')
            <li><a href="{{ route('admins.create.page') }}">Add New Admin</a></li>
            <li><a href="{{ route('tasks.force-delete.page') }}">Force Delete Task</a></li>
            <li><a href="{{ route('users.delete.page') }}">Delete User</a></li>
        @endif
    </ul>

</x-layout>
