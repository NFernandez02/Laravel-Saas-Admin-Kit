<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <!-- Links -->
        @auth
            <ul class="navbar-nav">
                @if (auth()->guard()->user()?->role?->name === 'admin')
                    <li class="nav-item">
                        <a href='/admin' >Admin</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit">Logout User</button>
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</nav>
