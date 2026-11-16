<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View { return view('admin.services.index', ['services' => Service::orderBy('sort_order')->paginate(15)]); }
    public function create(): View { return view('admin.services.form', ['service' => new Service()]); }
    public function store(Request $request): RedirectResponse { Service::create($this->data($request)); return redirect()->route('admin.services.index')->with('success', 'Informasi layanan ditambahkan.'); }
    public function edit(Service $service): View { return view('admin.services.form', compact('service')); }
    public function update(Request $request, Service $service): RedirectResponse { $service->update($this->data($request, $service)); return redirect()->route('admin.services.index')->with('success', 'Informasi layanan diperbarui.'); }
    public function destroy(Service $service): RedirectResponse { $service->delete(); return back()->with('success', 'Layanan dihapus.'); }
    private function data(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'], 'slug' => ['nullable', 'string', 'max:180'],
            'icon' => ['nullable', 'string', 'max:80'], 'summary' => ['nullable', 'string', 'max:500'],
            'requirements' => ['nullable', 'string'], 'procedure' => ['nullable', 'string'],
            'estimated_time' => ['nullable', 'string', 'max:100'], 'fee_information' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        // Slug is optional and is not shown in the form. Preserve it on edit,
        // otherwise derive it from the service name.
        $slugInput = $data['slug'] ?? ($service?->slug ?? $data['name']);
        $base = Str::slug($slugInput) ?: 'layanan'; $slug = $base; $i = 2;
        while (Service::where('slug', $slug)->when($service, fn ($q) => $q->whereKeyNot($service->id))->exists()) $slug = $base.'-'.$i++;
        $data['slug'] = $slug; $data['icon'] = ($data['icon'] ?? null) ?: 'bi-file-earmark-text';
        $data['sort_order'] = $data['sort_order'] ?? 0; $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
