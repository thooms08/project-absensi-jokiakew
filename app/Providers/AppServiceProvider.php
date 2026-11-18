<?php

namespace App\Providers;

use App\Models\ProfileSekolah;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        /*
         * Share $profileSekolah ke semua view layout (admin/guru/murid).
         * Komponen sidebar cukup pakai {{ $profileSekolah->nama_sekolah }}
         * tanpa perlu mengubah setiap controller.
         */
        View::composer([
            'admin.layouts.app',
            'guru.layouts.app',
            'murid.layouts.app',
        ], function ($view) {
            $view->with('profileSekolah', ProfileSekolah::getInstance());
        });
    }
}
