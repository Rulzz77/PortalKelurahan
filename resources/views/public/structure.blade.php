@extends('layouts.public')
@section('title', 'Struktur Organisasi')
@section('content')
<section class="page-hero"><div class="container"><div class="breadcrumb-lite"><a href="{{ route('home') }}">Beranda</a><i class="bi bi-chevron-right"></i><span>Struktur Organisasi</span></div><span class="section-kicker">TATA KELOLA ORGANISASI</span><h1>Struktur Organisasi</h1><p>Kenali susunan organisasi dan perangkat kelurahan.</p></div></section>
<section class="section-pad"><div class="container"><div class="section-heading centered-heading"><div><span class="section-kicker">PERANGKAT KELURAHAN</span><h2>Susunan Organisasi</h2><p>Bagan mengikuti susunan atasan dan bawahan yang dikelola administrator.</p></div></div>@if($staff->isNotEmpty())<div class="org-chart"><div class="org-level">@foreach($staff as $person)@include('public.partials.staff-node', ['person' => $person])@endforeach</div></div>@else<div class="empty-state"><i class="bi bi-diagram-3"></i><h5>Struktur organisasi belum tersedia</h5><p>Tambahkan data lurah, sekretaris, dan perangkat kelurahan melalui Dashboard → Struktur Organisasi.</p></div>@endif</div></section>
@endsection
