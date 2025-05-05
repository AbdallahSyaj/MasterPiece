<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'owner', 'tenant'])->default('tenant')->nullable();
            $table->timestamps();
        });

        // Admins Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Owners Table (replacing Doctors)
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('phone', 15);
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('bio')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // Apartments Table (replacing Doctor Details)
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('address');
            $table->string('city', 100);
            $table->decimal('price_per_night', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('max_guests');
            $table->boolean('has_wifi')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_kitchen')->default(false);
            $table->boolean('has_air_conditioning')->default(false);
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->enum('rating', ['1', '2', '3', '4', '5'])->default('1');
            $table->timestamps();
        });

        // Apartment Images Table (new)
        Schema::create('apartment_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Tenants Table (replacing Patients)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->integer('age');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // Available Dates Table (replacing Available Slots)
        Schema::create('available_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_booked')->default(false);
            $table->timestamps();
        });

        // Bookings Table (replacing Appointments)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->foreignId('available_date_id')->unique()->constrained('available_dates')->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('number_of_guests');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });

        // Reviews Table (new)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->enum('rating', ['1', '2', '3', '4', '5']);
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Form/Contact Table (keeping as is)
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('message')->nullable();
            $table->boolean('replied')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('available_dates');
        Schema::dropIfExists('apartment_images');
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('owners');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
        Schema::dropIfExists('forms');
    }
};