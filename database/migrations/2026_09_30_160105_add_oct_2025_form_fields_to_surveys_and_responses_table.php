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
            $table->date('date_of_resignation')->nullable()->after('last_working_date');
            $table->string('supervisor_name')->nullable()->after('reporting_manager');
            $table->string('section_division')->nullable()->after('department');
        });

        Schema::table('exit_responses', function (Blueprint $table) {
            // Ratings across the 20 specific areas from Oct 2025 Form
            $table->json('area_ratings')->nullable()->after('rating_tools');

            // Exact Reasons
            $table->string('main_reason_to_leave')->nullable()->after('primary_resignation_reason');
            $table->string('overseas_sub_option')->nullable()->after('main_reason_to_leave');
            $table->json('other_reasons_to_leave')->nullable()->after('resignation_factors');

            // Work Experience Liked
            $table->json('liked_most_aspects')->nullable()->after('other_reasons_to_leave');

            // Qualitative feedback
            $table->text('prevented_resignation')->nullable()->after('enjoyed_most');
            $table->string('recommend_company')->nullable()->after('would_recommend'); // Yes / No
            $table->string('open_to_reapply')->nullable()->after('would_return');     // Yes / No
            $table->text('other_comments')->nullable()->after('improvement_suggestions');

            // Signatures & Sign-offs
            $table->string('team_member_signature')->nullable();
            $table->date('team_member_signed_date')->nullable();
            $table->string('reviewed_director_hr')->nullable();
            $table->date('reviewed_director_hr_date')->nullable();
            $table->string('reviewed_company_head')->nullable();
            $table->date('reviewed_company_head_date')->nullable();
            $table->string('reviewed_group_chairman')->nullable();
            $table->date('reviewed_group_chairman_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exit_surveys', function (Blueprint $table) {
            $table->dropColumn(['date_of_resignation', 'supervisor_name', 'section_division']);
        });

        Schema::table('exit_responses', function (Blueprint $table) {
            $table->dropColumn([
                'area_ratings',
                'main_reason_to_leave',
                'overseas_sub_option',
                'other_reasons_to_leave',
                'liked_most_aspects',
                'prevented_resignation',
                'recommend_company',
                'open_to_reapply',
                'other_comments',
                'team_member_signature',
                'team_member_signed_date',
                'reviewed_director_hr',
                'reviewed_director_hr_date',
                'reviewed_company_head',
                'reviewed_company_head_date',
                'reviewed_group_chairman',
                'reviewed_group_chairman_date',
            ]);
        });
    }
};
