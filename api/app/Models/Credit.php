<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Artist;
use App\Models\Role;
use App\Models\Movie;

class Credit extends Model
{
    use HasFactory; protected $fillable = ['id','movieId','artistId','roleId']; protected $table = 'credit'; public $timestamps = false;
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artistId');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieId');
    }
}
