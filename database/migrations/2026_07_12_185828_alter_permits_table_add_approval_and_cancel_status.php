<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update ENUM 'status' to include 'Cancelled'
        DB::statement("ALTER TABLE permits MODIFY status ENUM('Draft', 'Submitted', 'Review Staff', 'Review Manager', 'Review Senior Manager', 'Revision', 'Active', 'Closed', 'Cancelled') DEFAULT 'Draft'");

        // 2. Add 'approval_signatures' column
        Schema::table('permits', function (Blueprint $table) {
            $table->json('approval_signatures')->nullable()->after('cancellation_signatures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('approval_signatures');
        });

        // Note: reverting ENUM in MySQL might fail if there are 'Cancelled' records.
        // It's safer to leave the ENUM as is on rollback or manually handle it.
        DB::statement("ALTER TABLE permits MODIFY status ENUM('Draft', 'Submitted', 'Review Staff', 'Review Manager', 'Review Senior Manager', 'Revision', 'Active', 'Closed') DEFAULT 'Draft'");
    }
};
