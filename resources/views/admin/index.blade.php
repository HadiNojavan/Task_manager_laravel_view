<x-layout title="Admin Panel">

    <h1>Admin Panel</h1>

    <p>Role: {{ $role }}</p>

    <ul>
        <li>Users (coming soon)</li>
        <li>Restore Task (coming soon)</li>
        <li>Assign Task (coming soon)</li>
        <li>Unassign Task (coming soon)</li>

        @if ($role == 'superadmin')
            <li>Add Admin (coming soon)</li>
            <li>Force Delete Task (coming soon)</li>
            <li>Delete User (coming soon)</li>
        @endif
    </ul>

</x-layout>
