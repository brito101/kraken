<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table = 'sites_view';

    /** Access */
    public function getLastCheckAttribute($value)
    {
        if ($value) {
            return date('d/m/Y H:i', strtotime($value));
        } else {
            return null;
        }
    }
}
