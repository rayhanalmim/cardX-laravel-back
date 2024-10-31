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
        Schema::create('cards', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('email'); // Email field
            $table->string('companyName'); // Company name field
            $table->string('designation'); // Designation field
            $table->string('name'); // Name field
            $table->string('phoneNumber')->nullable(); // Phone number (optional)
            $table->string('mobileNumber')->nullable(); // Mobile number (optional)
            $table->string('address'); // Address field
            $table->longText('image')->change(); // Use longText for large data
            $table->timestamps(); // Created at and updated at timestamps
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to the users table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
