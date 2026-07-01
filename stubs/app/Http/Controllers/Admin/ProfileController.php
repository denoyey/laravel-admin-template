<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateInfoRequest;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = auth()->user();

        if ($user->username === $request->username && $user->email === $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada perubahan data yang disimpan.',
            ], 422);
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'username' => $user->username,
                'email' => $user->email,
                'display_name' => $user->name ?? $user->username,
            ],
        ]);
    }
}
