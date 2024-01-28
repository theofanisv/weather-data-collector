<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weather_forecasters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('web_url')->nullable();
            $table->string('collector_class_key');
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_forecasters');
    }
};
