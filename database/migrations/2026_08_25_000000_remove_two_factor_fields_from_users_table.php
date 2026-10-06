<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = array_filter([
            Schema::hasColumn('users', 'two_factor_code') ? 'two_factor_code' : null,
            Schema::hasColumn('users', 'two_factor_expires_at') ? 'two_factor_expires_at' : null,
        ]);

        if ($columns) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_code', 6)->nullable()->after('remember_token');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
        });
    }
};