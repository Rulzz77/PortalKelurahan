<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Admin Portal Kelurahan</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-body">
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand"><span class="brand-symbol brand-symbol-light"><i class="bi bi-buildings"></i></span><span><b>Portal Kelurahan</b><small>Panel Administrator</small></span></a>
        <div class="sidebar-label">MENU UTAMA</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Ringkasan</a>
        <a href="{{ route('admin.posts.index') }}" class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"><i class="bi bi-newspaper"></i> Berita & Pengumuman</a>
        <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i class="bi bi-clipboard-check"></i> Informasi Pelayanan</a>
        <a href="{{ route('admin.staff.index') }}" class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a>
        <a href="{{ route('admin.galleries.index') }}" class="sidebar-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"><i class="bi bi-images"></i> Galeri Kegiatan</a>
        <a href="{{ route('admin.messages.index') }}" class="sidebar-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"><i class="bi bi-envelope"></i> Pesan Warga</a>
        <div class="sidebar-label mt-4">PENGATURAN</div>
        <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="bi bi-gear"></i> Identitas & Peta</a>
        <a href="{{ route('admin.account.edit') }}" class="sidebar-link {{ request()->routeIs('admin.account.*') ? 'active' : '' }}"><i class="bi bi-person-gear"></i> Akun Admin</a>
        <div class="sidebar-bottom"><a href="{{ route('home') }}" target="_blank" class="sidebar-link"><i class="bi bi-box-arrow-up-right"></i> Lihat Website</a><form action="{{ route('admin.logout') }}" method="POST">@csrf<button class="sidebar-link sidebar-logout" type="submit"><i class="bi bi-box-arrow-left"></i> Keluar</button></form></div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar"><button class="btn btn-light d-lg-none" type="button" id="sidebarToggle" aria-label="Buka menu"><i class="bi bi-list fs-4"></i></button><div><span class="topbar-eyebrow">AREA ADMINISTRATOR</span><strong>@yield('page-heading', 'Dashboard')</strong></div><div class="ms-auto d-flex align-items-center gap-2"><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><div class="d-none d-sm-block"><b class="d-block small">{{ auth()->user()->name }}</b><span class="text-secondary small">Administrator</span></div></div></header>
        <main class="admin-content">
            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if($errors->any())<div class="alert alert-danger"><strong>Periksa kembali isian berikut:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
