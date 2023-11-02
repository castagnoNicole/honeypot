<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Registration date</th>
            <th>Enable</th>
        </tr>
        </thead>
        <tbody>
        @isset($users)
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->status() }}</td>
                    <td>{{ $user->creationDate() }}</td>
                    <td>{{ $user->is_enabled ? 'Enabled' : 'Disabled'}}</td>
                    <td>
                        <form action="{{ route($user->is_enabled ? 'users.disable' : 'users.enable', $user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="submit"
                                   value="{{ $user->is_enabled ? 'Disable' : 'Enable' }}"
                                   class="btn {{ $user->is_enabled ? 'btn-primary' : 'btn-secondary' }}"
                                   style="min-width: 84px">
                        </form>
                    </td>
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

