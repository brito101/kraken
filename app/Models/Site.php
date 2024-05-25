<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'ip',
        'description',
        'technologies',
        'observations',
        'status',
        'user_id',
        'last_check',
    ];

    /**Rlationships */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /** Cascade actions */
    public static function boot(): void
    {
        parent::boot();

        static::deleting(function ($site) {
            $site->links()->delete();
        });
    }
}
