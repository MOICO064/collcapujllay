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
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumns('sales', ['reservation_name', 'reserved_for', 'reservation_date', 'is_reservation'])) {
                $table->dropColumn(['reservation_name', 'reserved_for', 'reservation_date', 'is_reservation']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('reservation_name')->nullable()->after('balance_due');
            $table->string('reserved_for')->nullable()->after('reservation_name');
            $table->timestamp('reservation_date')->nullable()->after('reserved_for');
            $table->boolean('is_reservation')->default(false)->after('balance_due');
        });
    }
};
