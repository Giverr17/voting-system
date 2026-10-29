<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * mat_no is now the primary login identity for voters.
     * Restore the unique constraint on users.mat_no so that
     * updateOrCreate(['mat_no' => ...]) works reliably.
     */
    public function up(): void
    {
        // Check if unique index already exists before adding it
        $indexes = collect(DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_mat_no_unique'"));

        if ($indexes->isEmpty()) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('mat_no');
            });
        }
    }

    public function down(): void
    {
        $indexes = collect(DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_mat_no_unique'"));

        if ($indexes->isNotEmpty()) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['mat_no']);
            });
        }
    }
};
