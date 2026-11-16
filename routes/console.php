<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('portal:about', function () {
    $this->info('Portal Informasi Kelurahan - Laravel 13');
})->purpose('Menampilkan informasi proyek portal kelurahan');
