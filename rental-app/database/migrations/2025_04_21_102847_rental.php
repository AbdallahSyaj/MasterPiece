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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('rental_id');
            $table->foreignId('property_id')->constrained('properties', 'property_id')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('monthly_rent', 12, 2);
            $table->integer('rent_due_day')->comment('يوم استحقاق الإيجار الشهري');
            $table->enum('rental_status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->string('contract_document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};