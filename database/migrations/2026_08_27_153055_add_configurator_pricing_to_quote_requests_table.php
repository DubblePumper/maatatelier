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
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->json('configuration')->nullable();
            $table->unsignedBigInteger('estimated_price_cents')->nullable();
            $table->unsignedBigInteger('benchmark_price_cents')->nullable();
            $table->string('pricing_version', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->dropColumn([
                'configuration',
                'estimated_price_cents',
                'benchmark_price_cents',
                'pricing_version',
            ]);
        });
    }
};
