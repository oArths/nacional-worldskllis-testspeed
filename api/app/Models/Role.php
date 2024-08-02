<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'movieId ', 'artistId ', 'roleId '];
    protected $table = 'Role';
    public $timestamps = false;
    public function User()
    {
        return $this->belongsToMany(User::class);
    }
}
