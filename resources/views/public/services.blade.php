@extends('layouts.public')
@section('title', 'Informasi Pelayanan')
@section('content')
<section class="page-hero"><div class="container"><div class="breadcrumb-lite"><a href="{{ route('home') }}">Beranda</a><i class="bi bi-chevron-right"></i><span>Pelayanan</span></div><span class="section-kicker">LAYANAN MASYARAKAT</span><h1>Informasi Pelayanan</h1><p>Temukan informasi persyaratan, prosedur, estimasi waktu, dan biaya layanan.</p></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">@forelse($services as $service)<div class="col-md-6 col-xl-4"><a href="{{ route('services.show', $service->slug) }}" class="service-card"><div class="service-icon"><i class="bi {{ $service->icon }}"></i></div><h5>{{ $service->name }}</h5><p>{{ $service->summary }}</p><span>Detail persyaratan <i class="bi bi-arrow-right"></i></span></a></div>@empty<div class="col-12"><div class="empty-state"><i class="bi bi-clipboard2-plus"></i><h5>Belum ada informasi layanan</h5><p>Administrator dapat menambahkan daftar layanan melalui dashboard.</p></div></div>@endforelse</div></div></section>
@endsection
