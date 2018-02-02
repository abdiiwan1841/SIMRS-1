<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Pasien\Http\Controllers'], function()
{
    Route::get('/pasien/cari', [
        'as' => 'pasien.cari',
        'uses' => 'PasienController@cari'
    ]);

    Route::resource('pasien', 'PasienController');
});