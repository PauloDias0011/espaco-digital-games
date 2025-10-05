<?php

declare(strict_types=1);

namespace Modules\Identity\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Identity\Database\Factories\UserFactory;
use Modules\Tenancy\App\Models\Tenant;
use App\Traits\HasTenantRoles;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $first_name
 * @property string $last_name
 * @property \Carbon\Carbon $birthdate
 * @property string $role
 * @property string|null $series_id
 * @property string|null $class_id
 * @property string $status
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Tenant $tenant
 * @property-read Series|null $series
 * @property-read Classroom|null $classroom
 * @property-read string $full_name
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUlids, HasTenantRoles, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'birthdate',
        'role',
        'series_id',
        'class_id',
        'status',
        'email_verified_at',
        'password',
        'user_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'birthdate' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['full_name'];

    /**
     * Get the tenant that owns the user.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the series that the user belongs to.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the classroom that the user belongs to.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check if the user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a tutor.
     */
    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'aluno';
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include users of a specific role.
     */
    public function scopeOfRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include users of a specific tenant.
     */
    public function scopeOfTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Get the user's total score (stub for Trail module).
     */
    public function getTotalScoreAttribute(): int
    {
        // TODO: Implementar quando o módulo Trail estiver pronto
        // Por enquanto, retorna um valor mockado
        return rand(0, 1000);
    }

    /**
     * Get the user's progress percentage (stub for Trail module).
     */
    public function getProgressPercentageAttribute(): float
    {
        // TODO: Implementar quando o módulo Trail estiver pronto
        // Por enquanto, retorna um valor mockado
        return rand(0, 100);
    }

    /**
     * Get the user's last activity (stub for Trail module).
     */
    public function getLastActivityAttribute(): ?string
    {
        // TODO: Implementar quando o módulo Trail estiver pronto
        // Por enquanto, retorna a data de criação
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if user has admin permissions.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    /**
     * Check if user can manage students.
     */
    public function canManageStudents(): bool
    {
        return $this->isAdmin() || $this->isTutor();
    }

    /**
     * Generate a unique user code.
     */
    public static function generateUserCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (static::where('user_code', $code)->exists());

        return $code;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->user_code)) {
                $user->user_code = static::generateUserCode();
            }
        });
    }

    /**
     * Find user by name and birthdate for student login.
     */
    public static function findByStudentCredentials(string $firstName, string $lastName, string $birthdate, string $tenantId): ?static
    {
        return static::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->where('birthdate', $birthdate)
            ->where('tenant_id', $tenantId)
            ->where('role', 'aluno')
            ->where('status', 'active')
            ->first();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
