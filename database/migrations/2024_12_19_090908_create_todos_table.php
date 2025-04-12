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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_classification_id');
            $table->foreign('contract_classification_id')
                ->references('id')
                ->on('contract_classifications')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('priority')->default("low"); // low / mid / high
            $table->mediumText('description')->nullable();
            $table->timestamp('due_to');
            $table->boolean("is_done")->default(false);
            $table->string('attachments')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
