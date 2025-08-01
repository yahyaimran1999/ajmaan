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
        Schema::connection('mysql_legacy')->table('mw_listing_users', function (Blueprint $table) {
            $table->string('facebook_id')->nullable()->after('apple_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_legacy')->table('mw_listing_users', function (Blueprint $table) {
            $table->dropColumn('facebook_id');
        });
    }
};
