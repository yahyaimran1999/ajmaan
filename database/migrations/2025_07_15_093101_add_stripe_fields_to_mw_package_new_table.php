<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::connection('mysql_legacy')->table('mw_package_new', function (Blueprint $table) {
            $table->string('stripe_plan_id')->nullable()->after('spnsored_ad_text');
            $table->string('stripe_price_id')->nullable()->after('stripe_plan_id');
        });
    }

    public function down()
    {
        Schema::connection('mysql_legacy')->table('mw_package_new', function (Blueprint $table) {
            $table->dropColumn(['stripe_plan_id', 'stripe_price_id']);
        });
    }
};
