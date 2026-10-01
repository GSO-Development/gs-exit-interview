<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExitResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'exit_survey_id',
        'next_career_step',
        'resignation_factors',
        'primary_resignation_reason',
        'resignation_elaboration',
        // Oct 2025 Form additions:
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
        // Legacy/Matrix ratings:
        'rating_job_clarity',
        'rating_workload',
        'rating_training',
        'rating_tools',
        'rating_teamwork',
        'rating_supervisor_feedback',
        'rating_supervisor_recognition',
        'rating_supervisor_fairness',
        'rating_supervisor_openness',
        'supervisor_comments',
        'rating_salary',
        'rating_increments',
        'rating_benefits',
        'rating_work_life_balance',
        'rating_company_culture',
        'would_recommend',
        'would_return',
        'enjoyed_most',
        'improvement_suggestions',
    ];

    protected function casts(): array
    {
        return [
            'resignation_factors' => 'array',
            'area_ratings' => 'array',
            'other_reasons_to_leave' => 'array',
            'liked_most_aspects' => 'array',
            'team_member_signed_date' => 'date',
            'reviewed_director_hr_date' => 'date',
            'reviewed_company_head_date' => 'date',
            'reviewed_group_chairman_date' => 'date',
        ];
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(ExitSurvey::class, 'exit_survey_id');
    }

    /** Average of job role & environment ratings. */
    public function jobRatingAverage(): float
    {
        $ratings = array_filter([
            $this->rating_job_clarity,
            $this->rating_workload,
            $this->rating_training,
            $this->rating_tools,
            $this->rating_teamwork,
        ]);

        return empty($ratings) ? 0 : round(array_sum($ratings) / count($ratings), 1);
    }

    /** Average of supervision ratings. */
    public function supervisorRatingAverage(): float
    {
        $ratings = array_filter([
            $this->rating_supervisor_feedback,
            $this->rating_supervisor_recognition,
            $this->rating_supervisor_fairness,
            $this->rating_supervisor_openness,
        ]);

        return empty($ratings) ? 0 : round(array_sum($ratings) / count($ratings), 1);
    }

    /** Average of compensation ratings. */
    public function compensationRatingAverage(): float
    {
        $ratings = array_filter([
            $this->rating_salary,
            $this->rating_increments,
            $this->rating_benefits,
            $this->rating_work_life_balance,
        ]);

        return empty($ratings) ? 0 : round(array_sum($ratings) / count($ratings), 1);
    }
}
