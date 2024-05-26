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
        Schema::create('profile_payment_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profile');
            $table->foreignId('attribute_id')->constrained('payment_method_attribute');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_payment_attribute');
    }
};
