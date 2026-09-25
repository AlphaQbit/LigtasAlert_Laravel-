<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('type');
            $table->string('facility_id')->index();
            $table->string('room');
            $table->text('message')->nullable();
            $table->string('status')->default('active')->index();
            $table->unsignedInteger('recipients')->default(0);
            $table->json('responders')->nullable();
            $table->string('acknowledged_by')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
