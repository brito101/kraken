<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page',
        'url',
        'title',
        'status',
        'site_id',
        'last_check',
        'signal',
        'observations'
    ];

    public function getLastCheckAttribute($value)
    {
        if ($value) {
            return date('d/m/Y H:i', strtotime($value));
        } else {
            return null;
        }
    }

    /**Relationships */

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
