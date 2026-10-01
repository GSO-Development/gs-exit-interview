<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExitSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'access_code',
        'employee_name',
        'employee_email',
        'employee_id',
        'designation',
        'department',
        'reporting_manager',
        'supervisor_name',
        'section_division',
        'company_id',
        'sent_by',
        'last_working_date',
        'date_of_resignation',
        'date_joined',
        'expires_at',
        'status',
        'submission_source',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'last_working_date' => 'date',
            'date_of_resignation' => 'date',
            'date_joined' => 'date',
            'expires_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function response(): HasOne
    {
        return $this->hasOne(ExitResponse::class);
    }

    /** Whether the token is still usable by the employee. */
    public function isAccessible(): bool
    {
        return $this->status === 'pending' && $this->expires_at->isFuture();
    }

    /** Days remaining until link expires. */
    public function daysRemaining(): int
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }

        return max(1, (int) ceil(now()->diffInSeconds($this->expires_at) / 86400));
    }

    /** Tenure in years (rounded to 1 decimal). */
    public function tenureYears(): ?string
    {
        if (! $this->date_joined || ! $this->last_working_date) {
            return null;
        }

        $years = round($this->date_joined->diffInMonths($this->last_working_date) / 12, 1);

        return $years.' Years';
    }

    /** Calculated overall satisfaction from all ratings. */
    public function overallSatisfaction(): ?float
    {
        $response = $this->response;
        if (! $response) {
            return null;
        }

        if (! empty($response->area_ratings) && is_array($response->area_ratings)) {
            $valid = array_filter($response->area_ratings, fn ($r) => is_numeric($r) && $r > 0);
            if (! empty($valid)) {
                return round(array_sum($valid) / count($valid), 1);
            }
        }

        $ratings = array_filter([
            $response->rating_job_clarity,
            $response->rating_workload,
            $response->rating_training,
            $response->rating_tools,
            $response->rating_teamwork,
            $response->rating_supervisor_feedback,
            $response->rating_supervisor_recognition,
            $response->rating_supervisor_fairness,
            $response->rating_supervisor_openness,
            $response->rating_salary,
            $response->rating_increments,
            $response->rating_benefits,
            $response->rating_work_life_balance,
            $response->rating_company_culture,
        ]);

        if (empty($ratings)) {
            return null;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }
}
