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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('property_id');
            $table->foreignId('owner_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('property_type');
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country');
            $table->decimal('area_size', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('monthly_rent', 12, 2);
            $table->decimal('security_deposit', 12, 2);
            $table->boolean('is_available')->default(true);
            $table->date('listed_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};