@extends('layouts.admin')
@section('title', 'Identitas & Peta')
@section('page-heading', 'Identitas & Peta')
@push('styles')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@section('content')
<div class="admin-page-heading"><div><h2>Identitas Website & Lokasi</h2><p>Informasi di sini dipakai pada header, footer, profil, dan halaman kontak publik.</p></div></div>
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">@csrf @method('PUT')<div class="row g-4"><div class="col-lg-7"><div class="admin-panel form-panel mb-4"><h5 class="form-section-title">Identitas kantor</h5><div class="mb-3"><label class="form-label">Nama kantor / kelurahan *</label><input class="form-control" name="office_name" value="{{ old('office_name',$settings->office_name) }}" required maxlength="180"></div><div class="mb-3"><label class="form-label">Tagline</label><input class="form-control" name="tagline" value="{{ old('tagline',$settings->tagline) }}" maxlength="220"></div><div class="mb-3"><label class="form-label">Logo kantor</label><input class="form-control" type="file" name="logo" accept="image/jpeg,image/png,image/webp"><div class="form-text">JPG/PNG/WebP, maksimal 2 MB.</div>@if($settings->logo_path)<img src="{{ asset('storage/'.$settings->logo_path) }}" class="settings-logo-preview mt-3" alt="Logo saat ini">@endif</div><hr><h5 class="form-section-title">Informasi kontak</h5><div class="mb-3"><label class="form-label">Alamat kantor</label><textarea class="form-control" name="office_address" rows="2">{{ old('office_address',$settings->office_address) }}</textarea></div><div class="row g-3"><div class="col-md-6"><label class="form-label">Nomor telepon</label><input class="form-control" name="phone" value="{{ old('phone',$settings->phone) }}"></div><div class="col-md-6"><label class="form-label">Email kantor</label><input class="form-control" type="email" name="email" value="{{ old('email',$settings->email) }}"></div><div class="col-12"><label class="form-label">Jam pelayanan</label><input class="form-control" name="office_hours" value="{{ old('office_hours',$settings->office_hours) }}" placeholder="Contoh: Senin–Jumat, 08.00–15.00 WIB"></div></div></div>
<div class="admin-panel form-panel"><h5 class="form-section-title">Tampilan beranda</h5><div class="mb-3"><label class="form-label">Judul utama beranda *</label><input class="form-control" name="hero_title" value="{{ old('hero_title',$settings->hero_title) }}" required maxlength="220"></div><div class="mb-3"><label class="form-label">Deskripsi beranda</label><textarea class="form-control" name="hero_subtitle" rows="3">{{ old('hero_subtitle',$settings->hero_subtitle) }}</textarea></div><div class="mb-3"><label class="form-label">Tentang / sejarah kelurahan</label><textarea class="form-control" name="about" rows="7" placeholder="Tuliskan profil dan sejarah resmi kelurahan...">{{ old('about',$settings->about) }}</textarea></div><div class="mb-3"><label class="form-label">Visi</label><textarea class="form-control" name="vision" rows="3" placeholder="Masukkan visi resmi kelurahan">{{ old('vision',$settings->vision) }}</textarea></div><div class="mb-3"><label class="form-label">Misi</label><textarea class="form-control" name="mission" rows="5" placeholder="Masukkan misi resmi kelurahan">{{ old('mission',$settings->mission) }}</textarea></div><div class="row g-3"><div class="col-md-4"><label class="form-label">Instagram URL</label><input class="form-control" name="instagram_url" value="{{ old('instagram_url',$settings->instagram_url) }}" placeholder="https://..."></div><div class="col-md-4"><label class="form-label">Facebook URL</label><input class="form-control" name="facebook_url" value="{{ old('facebook_url',$settings->facebook_url) }}" placeholder="https://..."></div><div class="col-md-4"><label class="form-label">YouTube URL</label><input class="form-control" name="youtube_url" value="{{ old('youtube_url',$settings->youtube_url) }}" placeholder="https://..."></div></div></div></div>
<div class="col-lg-5"><div class="admin-panel form-panel map-settings-panel"><h5 class="form-section-title">Titik lokasi kantor</h5><p class="small text-secondary">Klik peta untuk menentukan titik kantor atau geser pin ke lokasi yang benar. Periksa kembali alamat sebelum menyimpan.</p><div id="officePickerMap" class="office-picker-map"></div><div class="row g-2 mt-2"><div class="col-6"><label class="form-label">Latitude</label><input class="form-control" id="office_latitude" name="office_latitude" type="number" step="any" min="-90" max="90" value="{{ old('office_latitude',$settings->office_latitude) }}"></div><div class="col-6"><label class="form-label">Longitude</label><input class="form-control" id="office_longitude" name="office_longitude" type="number" step="any" min="-180" max="180" value="{{ old('office_longitude',$settings->office_longitude) }}"></div></div><button type="button" id="clearOfficeLocation" class="btn btn-sm btn-light mt-2">Hapus titik peta</button><div class="form-text mt-2">Peta memuat ubin peta dari OpenStreetMap dan memerlukan koneksi internet.</div></div><div class="admin-panel form-panel mt-4"><div class="alert alert-info small mb-0"><b>Privasi:</b> isi alamat kantor dan koordinat kantor publik saja. Jangan memasukkan alamat rumah pribadi atau data lokasi penduduk.</div><button class="btn btn-primary w-100 mt-3"><i class="bi bi-save me-2"></i>Simpan Semua Pengaturan</button></div></div></div></form>
@endsection
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapEl = document.getElementById('officePickerMap');
    if (!mapEl || typeof L === 'undefined') return;
    const latInput = document.getElementById('office_latitude');
    const lngInput = document.getElementById('office_longitude');
    const hasSaved = latInput.value !== '' && lngInput.value !== '';
    const initial = hasSaved ? [Number(latInput.value), Number(lngInput.value)] : [-2.5, 118];
    const map = L.map(mapEl).setView(initial, hasSaved ? 15 : 4.3);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
    let marker = null;
    function selectPoint(lat, lng) {
        latInput.value = Number(lat).toFixed(7);
        lngInput.value = Number(lng).toFixed(7);
        if (!marker) {
            marker = L.marker([lat, lng], {draggable:true}).addTo(map);
            marker.on('dragend', function (event) {
                const point = event.target.getLatLng();
                latInput.value = point.lat.toFixed(7);
                lngInput.value = point.lng.toFixed(7);
            });
        } else {
            marker.setLatLng([lat, lng]);
        }
    }
    if (hasSaved) { selectPoint(initial[0], initial[1]); }
    map.on('click', function (event) { selectPoint(event.latlng.lat, event.latlng.lng); });
    document.getElementById('clearOfficeLocation').addEventListener('click', function () { latInput.value = ''; lngInput.value = ''; if (marker) { map.removeLayer(marker); marker = null; } map.setView([-2.5,118], 4.3); });
    [latInput, lngInput].forEach(input => input.addEventListener('change', function () { if (latInput.value !== '' && lngInput.value !== '') { const lat = Number(latInput.value), lng = Number(lngInput.value); if (Number.isFinite(lat) && Number.isFinite(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180) { selectPoint(lat, lng); map.setView([lat,lng], 15); } } }));
    setTimeout(() => map.invalidateSize(), 200);
});
</script>
@endpush
