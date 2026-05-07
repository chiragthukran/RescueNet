<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calamities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // earthquake, flood, fire, cyclone, tsunami, landslide, industrial, other
            $table->string('custom_type')->nullable(); // for free-text when type is 'other'
            $table->text('description')->nullable();
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('radius_km', 8, 2)->default(5);
            $table->string('status')->default('active'); // active, contained, resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calamities');
    }
};
