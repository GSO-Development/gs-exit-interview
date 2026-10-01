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
        Schema::create('exit_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('employee_name');
            $table->string('employee_email');
            $table->string('employee_id')->nullable(); // EPF / Staff ID
            $table->string('designation');
            $table->string('department');
            $table->string('reporting_manager')->nullable();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('sent_by')->constrained('users')->restrictOnDelete();
            $table->date('last_working_date')->nullable();
            $table->date('date_joined')->nullable();
            $table->timestamp('expires_at');
            $table->enum('status', ['pending', 'submitted', 'expired', 'revoked'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_surveys');
    }
};
