<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SPE ID is the login identifier for bulk-imported voters. Nullable so
     * existing (self-registered) users are unaffected; unique so it can be a
     * primary login key.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('spe_id')->nullable()->unique()->after('mat_no');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['spe_id']);
            $table->dropColumn('spe_id');
        });
    }
};
