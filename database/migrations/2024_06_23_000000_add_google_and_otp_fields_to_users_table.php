<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('mysql_legacy')->table('mw_listing_users', function (Blueprint $table) {
            $table->string('google_id')->nullable();
            $table->string('otp', 150)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::connection('mysql_legacy')->table('mw_listing_users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'otp', 'otp_expires_at']);
        });
    }
};
