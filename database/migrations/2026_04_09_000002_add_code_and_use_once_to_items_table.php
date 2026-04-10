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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('use_once')->default(false)->after('description');
            $table->boolean('reservable')->default(false)->after('use_once');
            $table->unsignedInteger('capacity')->nullable()->after('reservable');
            $table->unsignedInteger('reservation_hours')->nullable()->after('capacity');
            $table->decimal('reservation_price', 10, 2)->nullable()->after('reservation_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['reservable', 'capacity', 'reservation_hours', 'reservation_price', 'use_once']);
        });
    }
};
