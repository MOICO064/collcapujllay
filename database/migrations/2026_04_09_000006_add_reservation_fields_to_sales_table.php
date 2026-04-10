<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('reservation_name')->nullable()->after('balance_due');
            $table->string('reserved_for')->nullable()->after('reservation_name');
            $table->timestamp('reservation_date')->nullable()->after('reserved_for');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['reservation_name', 'reserved_for', 'reservation_date']);
        });
    }
};
