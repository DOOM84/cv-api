<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $locals= ['ua', 'en', 'ru'];

        if (!request('lang') || !in_array(request('lang'), $locals )) {
            app()->setLocale('ua');
        }else{
            app()->setLocale(request('lang'));
        }

        Carbon::setLocale(app()->getLocale() == 'ua' ? 'uk' : app()->getLocale());
        Date::setlocale(app()->getLocale() == 'ua' ? 'uk' : app()->getLocale());
    }
}
