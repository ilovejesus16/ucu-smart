
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('building_announcement_reads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_announcement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('client_id', 100);

            $table->timestamp('read_at')->useCurrent();

            $table->timestamps();

            $table->unique(
                ['building_announcement_id', 'client_id'],
                'announcement_client_unique'
            );

            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('building_announcement_reads');
    }
};
