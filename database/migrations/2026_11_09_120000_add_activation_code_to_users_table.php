<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('activation_code')->nullable()->after('is_approved');          // code haché
            $table->timestamp('activation_code_expires_at')->nullable()->after('activation_code');
            $table->unsignedTinyInteger('activation_attempts')->default(0)->after('activation_code_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['activation_code', 'activation_code_expires_at', 'activation_attempts']);
        });
    }
};
