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
        Schema::create('archived_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('task_title', 50);
            $table->string('task_definition', 250);
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_tasks');
    }
};
