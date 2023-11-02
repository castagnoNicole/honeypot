<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Registration date</th>
        </tr>
        </thead>
        <tbody>
        @isset($users)
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="8"><em>No users found</em></td>
                </tr>
            @endforelse
        @endisset
        </tbody>
    </table>

</div>

