<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/"><img src="/storage/logo.png" alt="logo" width="100" class="d-inline-block align-top" loading="lazy"></a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto">
            <!--ADMIN-->
            @auth("admin")
            <li  class="nav-item">
                <a class="nav-link @if($current=="home") active @endif" href="/admin">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($current=="cadastros") active @endif" href="/admin/cadastros">Cadastros</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link @if($current=="agendamentos") active @endif" href="/admin/agendamentos/home">Agendamentos</a>
            </li>
            @endauth

            @auth("func")
            <li  class="nav-item">
                <a class="nav-link @if($current=="home") active @endif" href="/func">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($current=="dados") active @endif" href="/func/dados">Meus Dados</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link @if($current=="agendamentos") active @endif" href="/func/agendamentos">Meus Agendamentos</a>
            </li>
            @endauth

            @auth("web")
            <li  class="nav-item">
                <a class="nav-link @if($current=="home") active @endif" href="/home">Home</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link @if($current=="agendamentos") active @endif" href="/agendamentos">Meus Agendamentos</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link @if($current=="dados") active @endif" href="/dados">Meus Dados</a>
            </li>
            @endauth

            <!--DESLOGADO-->
            @guest
            <li class="nav-item">
                <a class="nav-link @if($current=="cadastro") active @endif" href="{{ route('register') }}">Cadastre-se</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($current=="login") active @endif" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($current=="login-func") active @endif" href="{{ route('func.login') }}">Login (Funcionário)</a>
            </li>

            <!--LOGADO-->
            @else
            <!--LOGOUT-->
            <li class="nav-item dropdown" class="nav-item">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown" class="nav-item">
                @if(Auth::user()->foto!="")
                <img id="foto_perfil" src="/storage/{{Auth::user()->foto}}" alt="foto_perfil">
                @endif
            </li>
            @endguest
        </ul>
    </div>
    </div>
</nav>