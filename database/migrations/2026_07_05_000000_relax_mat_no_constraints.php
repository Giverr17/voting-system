<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SPE ID (users.spe_id) is now the unique login identity. Matric number is
     * secondary and may be blank or repeated in bulk voter imports, so it must
     * no longer be a unique/required key on either table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['mat_no']);
        });

        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropUnique(['mat_no']);
            $table->string('mat_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Re-adding uniqueness assumes no duplicates remain in the data.
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->string('mat_no')->nullable(false)->change();
            $table->unique('mat_no');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('mat_no');
        });
    }
};
