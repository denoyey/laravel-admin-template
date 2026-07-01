<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\GlobalSearch\Providers\UserSearchProvider;
use App\Services\Admin\GlobalSearch\Providers\RoleSearchProvider;

class GlobalSearchController extends Controller
{
    /**
     * Daftarkan semua provider pencarian di sini.
     * Jika ada tabel/modul baru, cukup tambahkan class Provider-nya ke array ini.
     */
    protected array $providers = [
        UserSearchProvider::class,
        RoleSearchProvider::class,
    ];

    public function search(Request $request)
    {
        $request->validate(['query' => 'required|string|max:100']);
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [];

        foreach ($this->providers as $providerClass) {
            $provider = app($providerClass);
            $providerResults = $provider->search($query, 3);

            $results = array_merge($results, $providerResults);
        }

        return response()->json($results);
    }
}
