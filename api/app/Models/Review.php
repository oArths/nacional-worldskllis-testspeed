<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory; protected $fillable = ['id','userId','movieId','content','stars','createdAt']; protected $table = 'review'; public $timestamps = false;
}
