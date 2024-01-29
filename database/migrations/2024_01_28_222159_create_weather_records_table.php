<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_records', function (Blueprint $table) {
            $table->id();
            $table->dateTime('reference_datetime')->index();
            $table->string('period_type')->index(); // ['day', 'hour']
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
            $table->foreignId('weather_forecaster_id')->constrained()->restrictOnDelete();
            $table->float('temperature')->nullable()->index(); // in Celcius
            $table->float('precipitation')->nullable()->index(); // in mm
            // $table->index(['location_id', 'temperature']); // Compound index would be required in production.
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_records');
    }
};
