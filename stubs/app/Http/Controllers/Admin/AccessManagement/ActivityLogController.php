<?php

namespace App\Http\Controllers\Admin\AccessManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_any_activity', only: ['index']),
            new Middleware('permission:delete_activity', only: ['destroy']),
            new Middleware('permission:delete_any_activity', only: ['bulkDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.access-management.activity-logs.index');
    }

    public function show(Activity $activity_log)
    {
        return view('pages.admin.access-management.activity-logs.show', compact('activity_log'));
    }

    public function destroy(Activity $activity_log)
    {
        $activity_log->delete();

        return redirect()->back()->with('success', 'Riwayat aktivitas berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data aktivitas yang dipilih.');
        }

        $ids = array_slice(array_unique(array_filter(array_map('intval', $ids))), 0, 100);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        Activity::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids).' data aktivitas berhasil dihapus.');
    }
}
