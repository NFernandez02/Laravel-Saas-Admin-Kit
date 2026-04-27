<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <!-- Links -->
        <ul class="navbar-nav ms-auto">
            @auth
                <li class="nav-item">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit">Logout User</button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>
