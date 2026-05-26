<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <!-- Links -->
        @auth
            <ul class="navbar-nav">
                @if (auth()->user()?->role?->name === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href='{{route('admin.dashboard')}}'>Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href='{{ route('admin.users.index') }}' > Check Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href='{{ route('admin.roles.index') }}' > Check Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href='{{ route('admin.logs') }}' > Check Logs</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link" href='{{route('users.profile.index')}}'>Profile</a>
                    </li>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit">Logout User</button>
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</nav>
