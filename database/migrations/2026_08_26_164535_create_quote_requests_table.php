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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('project_type')->index();
            $table->boolean('dimensions_are_approximate')->default(true);
            $table->unsignedInteger('width_mm')->nullable();
            $table->unsignedInteger('height_mm')->nullable();
            $table->unsignedInteger('depth_mm')->nullable();
            $table->json('features')->nullable();
            $table->string('style');
            $table->string('budget');
            $table->string('timing');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->string('name', 100);
            $table->string('email', 254);
            $table->string('phone', 30);
            $table->string('postal_code', 10)->index();
            $table->timestamp('consent_at');
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
