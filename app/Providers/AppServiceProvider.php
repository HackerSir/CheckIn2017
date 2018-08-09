<?php

namespace App\Providers;

use App\Club;
use App\ImgurImage;
use App\Observers\ClubObserver;
use App\Observers\ImgurImageObserver;
use App\Observers\QrcodeObserver;
use App\Observers\RecordObserver;
use App\Qrcode;
use App\Record;
use Carbon\Carbon;
use Horizon;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \Monolog\Handler\MissingExtensionException
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //Carbon語系
        Carbon::setLocale(config('app.locale'));

        //Observers
        Qrcode::observe(QrcodeObserver::class);
        Record::observe(RecordObserver::class);
        ImgurImage::observe(ImgurImageObserver::class);
        Club::observe(ClubObserver::class);

        Horizon::auth(function ($request) {
            return \Laratrust::can('horizon.manage');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
