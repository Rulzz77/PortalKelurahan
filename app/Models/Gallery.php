<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title', 'image_path', 'caption', 'activity_date', 'sort_order', 'is_published'];
    protected function casts(): array { return ['activity_date' => 'date', 'is_published' => 'boolean']; }
}
