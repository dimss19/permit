<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('classification_permit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classification_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['permit_id', 'classification_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classification_permit');
        Schema::dropIfExists('classifications');
    }
};
