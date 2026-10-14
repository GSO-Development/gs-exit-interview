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
        Schema::table('exit_surveys', function (Blueprint $table) {
            $table->boolean('declaration_accepted')->default(false)->after('submission_source');
            $table->timestamp('declaration_accepted_at')->nullable()->after('declaration_accepted');
            $table->string('declaration_accepted_ip', 45)->nullable()->after('declaration_accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exit_surveys', function (Blueprint $table) {
            $table->dropColumn(['declaration_accepted', 'declaration_accepted_at', 'declaration_accepted_ip']);
        });
    }
};
