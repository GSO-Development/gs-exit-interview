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
        Schema::create('exit_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exit_survey_id')->constrained()->cascadeOnDelete();

            // Step 1: Career direction
            $table->string('next_career_step')->nullable();

            // Step 2: Resignation reasons
            $table->json('resignation_factors')->nullable(); // multi-select
            $table->string('primary_resignation_reason')->nullable();
            $table->text('resignation_elaboration')->nullable();

            // Step 3: Job role ratings (1-5)
            $table->unsignedTinyInteger('rating_job_clarity')->nullable();
            $table->unsignedTinyInteger('rating_workload')->nullable();
            $table->unsignedTinyInteger('rating_training')->nullable();
            $table->unsignedTinyInteger('rating_tools')->nullable();
            $table->unsignedTinyInteger('rating_teamwork')->nullable();

            // Step 4: Leadership ratings (1-5)
            $table->unsignedTinyInteger('rating_supervisor_feedback')->nullable();
            $table->unsignedTinyInteger('rating_supervisor_recognition')->nullable();
            $table->unsignedTinyInteger('rating_supervisor_fairness')->nullable();
            $table->unsignedTinyInteger('rating_supervisor_openness')->nullable();
            $table->text('supervisor_comments')->nullable();

            // Step 5: Compensation ratings (1-5)
            $table->unsignedTinyInteger('rating_salary')->nullable();
            $table->unsignedTinyInteger('rating_increments')->nullable();
            $table->unsignedTinyInteger('rating_benefits')->nullable();
            $table->unsignedTinyInteger('rating_work_life_balance')->nullable();

            // Step 6: Culture & recommendation
            $table->unsignedTinyInteger('rating_company_culture')->nullable();
            $table->string('would_recommend')->nullable(); // yes/maybe/no (eNPS)
            $table->string('would_return')->nullable();    // yes/no

            // Step 7: Final feedback
            $table->text('enjoyed_most')->nullable();
            $table->text('improvement_suggestions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_responses');
    }
};
