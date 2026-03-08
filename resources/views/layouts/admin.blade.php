<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Admin Dashboard')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6fb;
    font-family:'Segoe UI',sans-serif;
    overflow-x:hidden;
}

/* Smooth global */
*{
    transition: background 0.2s ease, color 0.2s ease;
}





/* ================= SIDEBAR ================= */
.sidebar{
    width:260px;
    min-height:100vh;
    background:linear-gradient(180deg,#1f1c2c,#302b63,#24243e);
    color:#fff;
    position:fixed;
    left:0;
    top:0;
    padding:30px 20px;
    transition:0.3s ease;
    z-index:1050;
    overflow-y:auto;
    scrollbar-width:thin;
}

.sidebar::-webkit-scrollbar{
    width:6px;
}
.sidebar::-webkit-scrollbar-thumb{
    background:rgba(255,255,255,0.3);
    border-radius:10px;
}

.sidebar.collapsed{
    width:80px;
}

.sidebar .brand{
    font-size:20px;
    font-weight:700;
    margin-bottom:40px;
}

.sidebar .nav-link{
    color:#cfd3ff;
    padding:12px 15px;
    border-radius:10px;
    margin-bottom:8px;
    transition:all 0.25s ease;
    display:flex;
    align-items:center;
    gap:10px;
}

.sidebar .nav-link:hover{
    background:rgba(255,255,255,0.12);
    transform:translateX(4px);
    color:#fff;
}

.sidebar .nav-link.active{
    background:#ffffff;
    color:#302b63;
    font-weight:600;
}

.sidebar.collapsed .nav-link span{
    display:none;
}

.sidebar.collapsed .brand span{
    display:none;
}

/* ================= HEADER ================= */
.topbar{
    margin-left:260px;
    padding:18px 30px;
    background:#ffffff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 12px rgba(0,0,0,0.05);
    transition:0.3s ease;
}

.topbar.collapsed{
    margin-left:80px;
}

/* ================= CONTENT ================= */
.content-wrapper{
    margin-left:260px;
    padding:30px;
    transition:0.3s ease;
}

.content-wrapper.collapsed{
    margin-left:80px;
}

/* ================= MOBILE ================= */
@media(max-width:992px){

    .sidebar{
        left:-260px;
    }

    .sidebar.active{
        left:0;
    }

    .topbar{
        margin-left:0 !important;
    }

    .content-wrapper{
        margin-left:0 !important;
        padding:20px;
    }

    .mobile-title{
        font-size:16px;
        font-weight:600;
    }
}

/* Backdrop */
.sidebar-backdrop{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    backdrop-filter:blur(2px);
    z-index:1040;
    display:none;
}

.sidebar-backdrop.active{
    display:block;
}

.btn-smooth{
    transition:0.2s ease;
}
.btn-smooth:hover{
    transform:translateY(-2px);
}
</style>
</head>
<body>

<!-- BACKDROP MOBILE -->
<div id="sidebarBackdrop" class="sidebar-backdrop" onclick="closeMobileSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="brand">
        <span>SmartTrash</span>
    </div>

    <ul class="nav flex-column">

        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
               📊 <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.tempat-sampah.index') }}"
               class="nav-link {{ request()->routeIs('admin.tempat-sampah.*') ? 'active' : '' }}">
               🗑 <span>Tempat Sampah</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
               👷 <span>Petugas</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.notifikasi') }}"
               class="nav-link {{ request()->routeIs('admin.notifikasi') ? 'active' : '' }}">
               🔔 <span>Notifikasi</span>
            </a>
        </li>

    </ul>
</div>

<!-- HEADER -->
<div class="topbar" id="topbar">

    <div class="d-flex align-items-center gap-3">

        <!-- Desktop Toggle -->
        <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline btn-smooth"
                onclick="toggleSidebar()">
            ☰
        </button>

        <!-- Mobile Toggle -->
        <button class="btn btn-outline-secondary btn-sm d-lg-none btn-smooth"
                onclick="toggleMobileSidebar()">
            ☰
        </button>

        <h5 class="mb-0 d-none d-lg-block">@yield('title')</h5>
        <span class="mobile-title d-lg-none">@yield('title')</span>
    </div>

    <div class="d-flex align-items-center gap-3">

        @php
            $notifCount = auth()->user()
                ->notifikasis()
                ->wherePivot('dibaca', false)
                ->count();
        @endphp

        <a href="{{ route('admin.notifikasi') }}"
           class="btn btn-warning btn-sm position-relative btn-smooth">
            🔔
            @if($notifCount > 0)
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                {{ $notifCount }}
            </span>
            @endif
        </a>

        <span class="fw-semibold d-none d-md-inline">
            {{ auth()->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-secondary btn-sm btn-smooth">
                Logout
            </button>
        </form>

    </div>
</div>

<!-- CONTENT -->
<div class="content-wrapper" id="content">
    @yield('content')
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('topbar').classList.toggle('collapsed');
    document.getElementById('content').classList.toggle('collapsed');
}

function toggleMobileSidebar(){
    document.getElementById('sidebar').classList.add('active');
    document.getElementById('sidebarBackdrop').classList.add('active');
}

function closeMobileSidebar(){
    document.getElementById('sidebar').classList.remove('active');
    document.getElementById('sidebarBackdrop').classList.remove('active');
}

/* Auto fix resize */
window.addEventListener('resize', function(){
    if(window.innerWidth > 992){
        document.getElementById('sidebar').classList.remove('active');
        document.getElementById('sidebarBackdrop').classList.remove('active');
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
