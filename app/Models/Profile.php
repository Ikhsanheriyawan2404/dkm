<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'address'];

    public function getTakeImageAttribute()
    {
        return '/storage/' .  $this->image;
    }
}
