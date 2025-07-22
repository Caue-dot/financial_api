<?php

use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MonthlyReport::class)->cascadeOnDelete();
            $table->decimal('value', 19,2);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('type', 1); 
            $table->string('category', 100);
            $table->boolean('recurrent');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
