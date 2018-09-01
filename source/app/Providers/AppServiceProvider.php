<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\ModulSistem\Entities\HakAksesModulSistem;
use Modules\ModulSistem\Entities\ModulSistem;
use Modules\PersonalisasiSistem\Entities\PersonalisasiSistem;
use Modules\User\Entities\Jabatan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->app['events']->listen(Authenticated::class, function ($e) {
            $jabatan = Jabatan::where('id', '=', $e->user->jabatan_id)->value('nama');

            view()->share('role', ucwords($jabatan));

            $navigations = ModulSistem::where('id', '=', HakAksesModulSistem::where('id_jabatan', '=', $e->user->jabatan_id)->value('id_modul'))->get();

            view()->share('navigations', $navigations);
        });

        $sistem = PersonalisasiSistem::where('id', '=', '1')->value('nama');

        view()->share('sistem', $sistem);
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
