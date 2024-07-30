<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory; protected $fillable = ['id','genreId','title','synopsis','durationMinutes','releaseDate','posterUrl','trailerUrl']; protected $table = 'movie'; public $timestamps = false;
}
