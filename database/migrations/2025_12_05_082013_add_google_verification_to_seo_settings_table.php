<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->string('google_site_verification')->nullable()->after('robots_txt');
            $table->string('bing_site_verification')->nullable()->after('google_site_verification');
            $table->string('pinterest_site_verification')->nullable()->after('bing_site_verification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn(['google_site_verification', 'bing_site_verification', 'pinterest_site_verification']);
        });
    }
};
