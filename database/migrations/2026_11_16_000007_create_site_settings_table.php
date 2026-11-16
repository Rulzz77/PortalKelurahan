<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('office_name')->default('Nama Kelurahan');
            $table->string('tagline')->default('Portal Informasi dan Pelayanan Masyarakat');
            $table->string('logo_path')->nullable();
            $table->string('office_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('office_hours')->nullable();
            $table->decimal('office_latitude', 10, 7)->nullable();
            $table->decimal('office_longitude', 10, 7)->nullable();
            $table->string('hero_title')->default('Selamat Datang di Portal Informasi Kelurahan');
            $table->text('hero_subtitle')->nullable();
            $table->longText('about')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('mission')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('site_settings'); }
};
