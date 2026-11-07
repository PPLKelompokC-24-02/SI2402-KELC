<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profession;
use App\Models\Role;
use App\Models\SavedProfession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FavoriteProfessionController extends Controller
{
    public function store(Request $request, Profession $profession): JsonResponse|RedirectResponse
    {
        $user = $request->user('web') ?? $request->user();
        abort_unless($user && $user->hasRole(Role::MAHASISWA), 403, 'Akses hanya untuk mahasiswa.');
        abort_unless($profession->is_active && $profession->publication_status === 'published', 404, 'Profesi tidak ditemukan.');

        if ($user->hasSavedProfession($profession)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'saved' => true,
                    'message' => 'Profesi sudah menjadi favorit.',
                ], 200);
            }

            return back()->with('info', 'Profesi sudah menjadi favorit.');
        }

        $user->savedProfessions()->syncWithoutDetaching([$profession->id]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Profesi berhasil ditambahkan ke Favorit Saya.',
            ], 200);
        }

        return back()->with('status', 'Profesi berhasil ditambahkan ke Favorit Saya.');
    }

    public function destroy(Request $request, Profession $profession): JsonResponse|RedirectResponse
    {
        $user = $request->user('web') ?? $request->user();
        abort_unless($user && $user->hasRole(Role::MAHASISWA), 403, 'Akses hanya untuk mahasiswa.');

        $user->savedProfessions()->detach($profession->id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Profesi berhasil dihapus dari Favorit Saya.',
            ], 200);
        }

        return back()->with('status', 'Profesi berhasil dihapus dari Favorit Saya.');
    }

    public function toggle(Request $request, Profession $profession): JsonResponse|RedirectResponse
    {
        $user = $request->user('web') ?? $request->user();
        abort_unless($user && $user->hasRole(Role::MAHASISWA), 403, 'Akses hanya untuk mahasiswa.');
        abort_unless($profession->is_active && $profession->publication_status === 'published', 404, 'Profesi tidak ditemukan.');

        $isSaved = $user->hasSavedProfession($profession);

        if ($isSaved) {
            $user->savedProfessions()->detach($profession->id);
            $message = 'Profesi berhasil dihapus dari Favorit Saya.';
            $saved = false;
        } else {
            $user->savedProfessions()->syncWithoutDetaching([$profession->id]);
            $message = 'Profesi berhasil ditambahkan ke Favorit Saya.';
            $saved = true;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'saved' => $saved,
                'message' => $message,
            ], 200);
        }

        return back()->with('status', $message);
    }

    public function destroyPivot(Request $request, SavedProfession $savedProfession): JsonResponse|RedirectResponse
    {
        $user = $request->user('web') ?? $request->user();
        abort_unless($user && $user->hasRole(Role::MAHASISWA), 403, 'Akses hanya untuk mahasiswa.');
        Gate::authorize('delete', $savedProfession);

        $savedProfession->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Profesi berhasil dihapus dari Favorit Saya.',
            ], 200);
        }

        return back()->with('status', 'Profesi berhasil dihapus dari Favorit Saya.');
    }
}

