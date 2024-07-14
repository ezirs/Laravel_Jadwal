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
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->date('start')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->date('end')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->boolean('use_datetime')->default(false);
            $table->string('link')->nullable();
            $table->string('schedule_color')->default('#3788d8');
            $table->text('description')->nullable();
            $table->enum('status', ['accepted', 'pending', 'rejected']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
