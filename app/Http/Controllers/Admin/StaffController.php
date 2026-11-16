<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('admin.staff.index', ['staff' => Staff::with('parent')->orderBy('sort_order')->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.staff.form', ['person' => new Staff(), 'parents' => Staff::orderBy('sort_order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->data($request);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('staff', 'public');
        }
        Staff::create($data);

        return redirect()->route('admin.staff.index')->with('success', 'Data struktur organisasi ditambahkan.');
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.form', [
            'person' => $staff,
            'parents' => Staff::where('id', '!=', $staff->id)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $data = $this->data($request, $staff);
        if ($request->hasFile('photo')) {
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('staff', 'public');
        }
        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Data pegawai diperbarui.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        $staff->delete();

        return back()->with('success', 'Data pegawai dihapus. Bawahan langsung akan menjadi tanpa atasan.');
    }

    private function data(Request $request, ?Staff $person = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'position' => ['required', 'string', 'max:160'],
            'department' => ['nullable', 'string', 'max:160'],
            'bio' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:staff,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($person && $parentId) {
            $cursor = Staff::find($parentId);
            while ($cursor) {
                if ($cursor->id === $person->id) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'Susunan atasan tidak boleh membentuk hubungan melingkar.',
                    ]);
                }
                $cursor = $cursor->parent;
            }
        }

        unset($data['photo']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
