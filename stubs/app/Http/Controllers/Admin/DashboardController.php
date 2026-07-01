<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (! Cache::has('activitylog_cleaned_today')) {
            $lock = Cache::lock('activitylog_clean_lock', 10);

            if ($lock->get()) {
                try {
                    Artisan::call('activitylog:clean');
                    Cache::put('activitylog_cleaned_today', true, now()->endOfDay());
                } finally {
                    $lock->release();
                }
            }
        }

        $totalUser = User::count();
        $totalRole = Role::count();
        $totalActivity = Activity::count();

        return view('pages.admin.dashboard.index', compact(
            'totalUser', 'totalRole', 'totalActivity'
        ));
    }
}
