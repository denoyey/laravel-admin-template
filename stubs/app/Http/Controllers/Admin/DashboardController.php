<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\DetailService;
use App\Models\Faq;
use App\Models\LogoInstansi;
use App\Models\Portofolio;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

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

        $totalPortofolio = Portofolio::count();
        $totalService = Service::count();
        $totalSubService = SubService::count();
        $totalDetailService = DetailService::count();
        $totalLogoInstansi = LogoInstansi::count();
        $totalFaq = Faq::count();
        $totalContact = Contact::count();
        $totalUser = User::count();
        $totalActivity = Activity::count();

        return view('pages.admin.dashboard.index', compact(
            'totalPortofolio', 'totalService', 'totalSubService',
            'totalDetailService', 'totalLogoInstansi', 'totalFaq',
            'totalContact', 'totalUser', 'totalActivity'
        ));
    }
}
