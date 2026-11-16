<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'summary', 'requirements', 'procedure', 'estimated_time', 'fee_information', 'sort_order', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
