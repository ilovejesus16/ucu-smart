<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Keep the record even if the user account is deleted.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Save the user's name as it was when the action occurred.
            $table->string('user_name')->nullable();

            // Example: Created, Updated, Deleted, Approved, Imported
            $table->string('action');

            // Example: Users, Buildings, Rooms, Schedules
            $table->string('module');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('created_at');
            $table->index('module');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
