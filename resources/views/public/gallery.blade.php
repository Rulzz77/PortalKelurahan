@extends('layouts.public')
@section('title', 'Galeri Kegiatan')
@section('content')
<section class="page-hero"><div class="container"><div class="breadcrumb-lite"><a href="{{ route('home') }}">Beranda</a><i class="bi bi-chevron-right"></i><span>Galeri</span></div><span class="section-kicker">CERITA DALAM GAMBAR</span><h1>Galeri Kegiatan</h1><p>Dokumentasi kegiatan dan aktivitas kelurahan.</p></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">@forelse($items as $item)<div class="col-6 col-lg-4"><a class="gallery-card" href="{{ asset('storage/'.$item->image_path) }}" data-lightbox><img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}"><span class="gallery-overlay"><b>{{ $item->title }}</b><small>{{ $item->activity_date?->translatedFormat('d F Y') }}</small></span></a></div>@empty<div class="col-12"><div class="empty-state"><i class="bi bi-images"></i><h5>Galeri belum tersedia</h5><p>Foto kegiatan yang diunggah administrator akan tampil di halaman ini.</p></div></div>@endforelse</div><div class="mt-5">{{ $items->links() }}</div></div></section>
@endsection
