<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->json('phoneNumber')->nullable()->change(); // Set phoneNumber to JSON type
        });
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('phoneNumber')->nullable()->change(); // Revert to string if necessary
        });
    }
};
