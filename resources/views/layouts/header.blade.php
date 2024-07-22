<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">App Name</a>
        @auth
        <form class="d-flex" action="{{ route('logout') }}" method="POST">@csrf<input type="submit" class="btn btn-link text-white" value="Logout"></form>
        @endauth

        @guest
        <div class="d-flex">
            <a href="{{ route('login') }}" class="btn btn-link text-white">Login</a>
            <a href="{{ route('register') }}" class="btn btn-link text-white">Register</a>
        </div>
        @endguest
    </div>
</nav>
