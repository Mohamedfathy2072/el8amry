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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // Car identification
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->string('car_model_name')->nullable();
            $table->year('model_year');
            $table->date('license_expire_date')->nullable(); // License Valid To
            $table->string('location')->nullable(); // Nasr City...
            $table->string('description')->nullable();
            $table->string('vehicle_category')->nullable();

            // Vehicle specifications
            $table->foreignId('body_style_id')->nullable()->constrained()->onDelete('set null'); // SUV, Sedan, Hatchback, etc.
            $table->foreignId('type_id')->nullable()->constrained()->onDelete('set null'); // vehicle category
            $table->foreignId('fuel_economy_id')->nullable()->constrained()->onDelete('set null'); // Fuel economy reference
            $table->foreignId('transmission_type_id')->nullable()->constrained()->onDelete('set null'); // Automatic, Manual, etc.
            $table->foreignId('drive_type_id')->nullable()->constrained()->onDelete('set null'); // FWD, RWD, AWD, etc.
            $table->foreignId('engine_type_id')->nullable()->constrained()->onDelete('set null'); // Petrol, Diesel, Electric, Hybrid
            $table->integer('engine_capacity_cc')->nullable(); // Engine capacity in cc

            // Physical attributes
            $table->json('color');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null'); // Size Long, Width, Height

            // Performance & condition
            $table->unsignedInteger('mileage'); // Total kilometers driven
            $table->foreignId('horsepower_id')->nullable()->constrained()->onDelete('set null'); // Speed
            $table->foreignId('vehicle_status_id')->nullable()->constrained()->onDelete('set null'); // New, Used, etc.
            $table->json('refurbishment_status'); // Refurbishment status if applicable

            // Pricing
            $table->decimal('price'); // Sale price
            $table->decimal('discount', 8, 2)->default(0); // Discount amount
            $table->decimal('monthly_installment')->nullable(); // Monthly payment option
            $table->decimal('down_payment')->nullable(); // Down payment
            $table->enum('payment_option', ['cash', 'installment'])->nullable();

            // Classification
            $table->foreignId('trim_id')->nullable()->constrained()->onDelete('set null'); // 1st Category (trim/grade)
            $table->text('fcm_token')->nullable();  // Notification token for push notifications

            $table->timestamps();
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null'); // User who owns the car
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
