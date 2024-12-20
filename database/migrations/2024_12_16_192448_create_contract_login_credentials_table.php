<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contract_login_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('login_credentials_id');
            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->onDelete('cascade');
            $table->foreign('login_credentials_id')
                ->references('id')
                ->on('login_credentials')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_login_credentials');
    }
};
