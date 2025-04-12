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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('on_date');
            $table->mediumText('description')->nullable();
            $table->boolean('is_done')->default(false);
            $table->unsignedBigInteger('contract_classification_id');
            $table->foreign('contract_classification_id')
                ->references('id')
                ->on('contract_classifications')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
