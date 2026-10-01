<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'company_id', 'azure_id', 'auth_provider'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The company this user is restricted to (null = unrestricted / super admin).
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Returns true if the user is scoped to a specific subsidiary.
     */
    public function isSubsidiaryHR(): bool
    {
        return $this->hasRole('subsidiary_hr_manager') && ! $this->hasRole('super_admin');
    }

    /**
     * The company IDs this user can access (all for super_admin, own for subsidiary HR).
     *
     * @return array<int>|null null means no restriction
     */
    public function accessibleCompanyIds(): ?array
    {
        if ($this->isSubsidiaryHR() && $this->company_id) {
            return [$this->company_id];
        }

        return null; // null = unrestricted
    }
}
