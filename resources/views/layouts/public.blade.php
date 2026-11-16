<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings->office_name ?? 'Portal Kelurahan') - {{ $settings->office_name ?? 'Portal Kelurahan' }}</title>
    <meta name="description" content="{{ $settings->tagline ?? 'Portal informasi dan pelayanan masyarakat.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="site-body">
    <div class="top-strip">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><i class="bi bi-info-circle me-1"></i> {{ $settings->tagline ?? 'Portal Informasi dan Pelayanan Masyarakat' }}</span>
            <span><i class="bi bi-clock me-1"></i> {{ $settings->office_hours ?: 'Jam pelayanan dapat dilihat pada informasi kantor.' }}</span>
        </div>
    </div>
    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand-lg container py-3">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
                @if($settings->logo_path)
                    <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo {{ $settings->office_name }}" class="brand-logo">
                @else
                    <span class="brand-symbol"><i class="bi bi-buildings"></i></span>
                @endif
                <span class="brand-copy"><strong>{{ $settings->office_name }}</strong><small>{{ $settings->tagline }}</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka navigasi"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Profil</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('structure') ? 'active' : '' }}" href="{{ route('structure') }}">Struktur</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('services*') ? 'active' : '' }}" href="{{ route('services') }}">Pelayanan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}" href="{{ route('news') }}">Berita</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('announcements') ? 'active' : '' }}" href="{{ route('announcements') }}">Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Galeri</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary-soft btn-sm px-3" href="{{ route('contact') }}">Hubungi Kami <i class="bi bi-arrow-up-right ms-1"></i></a></li>
                </ul>
            </div>
        </nav>
    </header>

    @if(session('success'))<div class="container mt-3"><div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div></div>@endif
    @if(session('info'))<div class="container mt-3"><div class="alert alert-info alert-dismissible fade show" role="alert">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div></div>@endif
    @if($errors->any())<div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Periksa kembali isian formulir:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div></div>@endif

    <main>@yield('content')</main>

    <footer class="site-footer mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-3"><span class="brand-symbol brand-symbol-light"><i class="bi bi-buildings"></i></span><div><h5 class="mb-0 text-white">{{ $settings->office_name }}</h5><small class="text-white-50">{{ $settings->tagline }}</small></div></div>
                    <p class="text-white-50 mb-0">Media informasi untuk mendukung keterbukaan informasi, komunikasi, dan pelayanan kepada masyarakat.</p>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="footer-title">Navigasi</h6>
                    <a href="{{ route('profile') }}">Profil Kelurahan</a><a href="{{ route('structure') }}">Struktur Organisasi</a><a href="{{ route('services') }}">Informasi Pelayanan</a><a href="{{ route('announcements') }}">Pengumuman</a>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="footer-title">Kontak Kantor</h6>
                    <p><i class="bi bi-geo-alt me-2"></i>{{ $settings->office_address ?: 'Alamat kantor belum diatur.' }}</p>
                    <p><i class="bi bi-telephone me-2"></i>{{ $settings->phone ?: 'Nomor telepon belum diatur.' }}</p>
                    <p><i class="bi bi-envelope me-2"></i>{{ $settings->email ?: 'Email belum diatur.' }}</p>
                    <div class="d-flex gap-3 mt-3">
                        @if($settings->instagram_url)<a class="social-link" href="{{ $settings->instagram_url }}" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>@endif
                        @if($settings->facebook_url)<a class="social-link" href="{{ $settings->facebook_url }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>@endif
                        @if($settings->youtube_url)<a class="social-link" href="{{ $settings->youtube_url }}" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>@endif
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-wrap justify-content-between gap-2 text-white-50 small"><span>© {{ date('Y') }} {{ $settings->office_name }}. Seluruh hak cipta dilindungi.</span><a href="{{ route('admin.login') }}" class="footer-admin-link">Admin</a></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
