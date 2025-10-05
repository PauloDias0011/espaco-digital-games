<?php

declare(strict_types=1);

namespace Modules\Identity\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Identity\Database\Factories\ClassroomFactory;
use Modules\Tenancy\App\Models\Tenant;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property int $year
 * @property string|null $series_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Tenant $tenant
 * @property-read Series|null $series
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 */
class Classroom extends Model
{
    use HasFactory, HasUlids;

    /**
     * The table associated with the model.
     */
    protected $table = 'classes';

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
        'name',
        'year',
        'series_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Get the tenant that owns the classroom.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the series that the classroom belongs to.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the users for the classroom.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'class_id');
    }

    /**
     * Get the students for the classroom.
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'class_id')
            ->where('role', 'aluno');
    }

    /**
     * Get the tutors for the classroom.
     */
    public function tutors(): HasMany
    {
        return $this->hasMany(User::class, 'class_id')
            ->where('role', 'tutor');
    }

    /**
     * Scope a query to only include classrooms of a specific tenant.
     */
    public function scopeOfTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include classrooms of a specific year.
     */
    public function scopeOfYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope a query to only include classrooms of a specific series.
     */
    public function scopeOfSeries($query, string $seriesId)
    {
        return $query->where('series_id', $seriesId);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ClassroomFactory
    {
        return ClassroomFactory::new();
    }
}
