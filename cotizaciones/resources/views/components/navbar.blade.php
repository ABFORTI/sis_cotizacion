<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-inner">
            <a class="navbar-brand" href="/">
                Sistema de Cotizaciones
            </a>
            
            <!-- Hamburger button - animated -->
            <button id="hamburger-btn" class="hamburger-button">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>

            <!-- Desktop navigation -->
            <div class="desktop-nav font-bold">
                @auth
                <span class="nav-user">Hola, {{ Auth::user()->name }}</span>
                <br>
                @if (Auth::user()->isVentasLike() || Auth::user()->role === 'costeos')
                <a href="/" class="nav-link">Inicio</a>
                <a href="/cotizaciones" class="nav-link">Requisición de cotización</a>
                @if (Auth::user()->isGerenteVentas())
                <a href="{{ route('gerente.ventas.index') }}" class="nav-link">Supervisión Ventas</a>
                @endif
                @elseif (Auth::user()->role === 'admin')
                <a href="/" class="nav-link">Inicio</a>
                <a href="{{ route('administrador.admin.index') }}" class="nav-link admin-link">🛡️ Panel Admin</a>
                <a href="{{ route('usuarios.index') }}" class="nav-link admin-link"><i class="fa-solid fa-users mr-2"></i> Panel Usuarios </a>
                @endif
                <a href="{{ route('cerrar-sesion') }}" class="nav-link">Cerrar sesión</a>
                @else
                <a href="{{ route('login') }}" class="nav-link">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="nav-link">Registrarse</a>
                @endauth
            </div>
        </div>

        <!-- Mobile menu - simple -->
        <div id="mobile-nav" class="mobile-nav font-bold">
            @auth
            <div class="mobile-user">Hola, {{ Auth::user()->name }}</div>
            
            @if (Auth::user()->isVentasLike() || Auth::user()->role === 'costeos')
                <a href="/" class="mobile-nav-link">Inicio</a>
                <a href="/cotizaciones" class="mobile-nav-link">Requisición de cotización</a>
                @if (Auth::user()->isGerenteVentas())
                <a href="{{ route('gerente.ventas.index') }}" class="mobile-nav-link">Supervisión Ventas</a>
                @endif
            @elseif (Auth::user()->role === 'admin')
                <a href="/" class="mobile-nav-link">Inicio</a>
                <a href="{{ route('administrador.admin.index') }}" class="mobile-nav-link">🛡️ Panel Admin</a>
                <a href="{{ route('usuarios.index') }}" class="mobile-nav-link"><i class="fa-solid fa-users mr-2"></i> Panel Usuarios </a>
            @endif
            <a href="{{ route('cerrar-sesion') }}" class="mobile-nav-link">Cerrar sesión</a>
            @else
            <a href="{{ route('login') }}" class="mobile-nav-link">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="mobile-nav-link">Registrarse</a>
            @endauth
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileNav = document.getElementById('mobile-nav');
    
    hamburgerBtn.addEventListener('click', function() {
        mobileNav.classList.toggle('show');
        hamburgerBtn.classList.toggle('active');
    });
    
    // Cerrar menú al hacer click fuera
    document.addEventListener('click', function(event) {
        if (!hamburgerBtn.contains(event.target) && !mobileNav.contains(event.target)) {
            mobileNav.classList.remove('show');
            hamburgerBtn.classList.remove('active');
        }
    });
    
    // Cerrar menú al hacer click en un enlace
    const mobileLinks = mobileNav.querySelectorAll('.mobile-nav-link');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileNav.classList.remove('show');
            hamburgerBtn.classList.remove('active');
        });
    });
});
</script>