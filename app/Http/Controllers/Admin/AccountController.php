<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(): View { return view('admin.account'); }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'string', 'confirmed', 'min:10'],
        ]);
        $user = $request->user();
        $user->name = $data['name'];
        if (! empty($data['password'] ?? null)) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'Profil akun berhasil diperbarui.');
    }
}
