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
        Schema::create('event_notify_channel', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::rename('event_notify_channel', 'event_notify_channels');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_notify_channel');

        Schema::rename('event_notify_channels', 'event_notify_channel');
    }
};
