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
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->decimal('total_income',19,2)->default(0)->change();
            $table->decimal('total_expense',19,2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->decimal('total_income',19,2);
            $table->decimal('total_expense',19,2);
        });
    }
};
