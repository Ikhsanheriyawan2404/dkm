<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'position', 'image'];

    public function getTakeImageAttribute()
    {
        return '/storage/' . $this->image;
    }
}
