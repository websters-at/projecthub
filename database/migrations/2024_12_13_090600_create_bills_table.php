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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_classification_id');
            $table->foreign('contract_classification_id')
                ->references('id')
                ->on('contract_classifications')
                ->onDelete('cascade');
            $table->boolean('is_flat_rate')->default(false);
            $table->double('flat_rate_amount')->nullable();
            $table->integer('flat_rate_hours')->nullable();
            $table->integer('flat_rate_minutes')->nullable();
            $table->boolean('billed')->default(false);
            $table->string('name');
            $table->boolean('is_payed')
                ->default(false);
            $table->double('hourly_rate')
                ->nullable();
            $table->date('created_on')
                ->nullable();
            $table->date('due_to')
                ->nullable();
            $table->mediumText('description')
                ->nullable();
            $table->string('attachments')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
