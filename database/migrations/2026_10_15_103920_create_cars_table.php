<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('make');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('mileage')->default(0);

            $table->string('transmission')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('condition')->default('used');

            $table->text('description')->nullable();

            $table->string('status')->default('available');

            $table->timestamps();

            $table->index(['category_id', 'status']);
            $table->index(['make', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};