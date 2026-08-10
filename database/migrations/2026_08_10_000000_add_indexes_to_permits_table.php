<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('tipe');
            $table->index('submitted_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tipe']);
            $table->dropIndex(['submitted_at']);
            $table->dropIndex(['created_at']);
        });
    }
};
